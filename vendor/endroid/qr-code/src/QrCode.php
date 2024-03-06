<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCode;

use BaconQrCode\Encoder\Encoder;
use Endroid\QrCode\Exception\InvalidPathException;
use Endroid\QrCode\Exception\UnsupportedExtensionException;
use Endroid\QrCode\Writer\WriterInterface;

class QrCode implements QrCodeInterface
{
    const LABEL_FONT_PATH_DEFAULT = __DIR__.'/../assets/fonts/noto_sans.otf';

    private $text;

    private $size = 300;
    private $margin = 10;

    private $foregroundColor = [
        'r' => 0,
        'g' => 0,
        'b' => 0,
        'a' => 0,
    ];

    private $backgroundColor = [
        'r' => 255,
        'g' => 255,
        'b' => 255,
        'a' => 0,
    ];

    private $encoding = 'UTF-8';
    private $roundBlockSize = true;
    private $errorCorrectionLevel;

    private $logoPath;
    private $logoWidth;
    private $logoHeight;

    private $label;
    private $labelFontSize = 16;
    private $labelFontPath = self::LABEL_FONT_PATH_DEFAULT;
    private $labelAlignment;
    private $labelMargin = [
        't' => 0,
        'r' => 10,
        'b' => 10,
        'l' => 10,
    ];

    private $writerRegistry;
    private $writer;
    private $writerOptions = [];
    private $validateResult = false;

    public function __construct(string $text = '')
    {
        $this->text = $text;

        $this->errorCorrectionLevel = new ErrorCorrectionLevel(ErrorCorrectionLevel::LOW);
        $this->labelAlignment = new LabelAlignment(LabelAlignment::CENTER);

        $this->createWriterRegistry();
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setMargin(int $margin): void
    {
        $this->margin = $margin;
    }

    public function getMargin(): int
    {
        return $this->margin;
    }

    public function setForegroundColor(array $foregroundColor): void
    {
        if (!isset($foregroundColor['a'])) {
            $foregroundColor['a'] = 0;
        }

        foreach ($foregroundColor as &$color) {
            $color = intval($color);
        }

        $this->foregroundColor = $foregroundColor;
    }

    public function getForegroundColor(): array
    {
        return $this->foregroundColor;
    }

    public function setBackgroundColor(array $backgroundColor): void
    {
        if (!isset($backgroundColor['a'])) {
            $backgroundColor['a'] = 0;
        }

        foreach ($backgroundColor as &$color) {
            $color = intval($color);
        }

        $this->backgroundColor = $backgroundColor;
    }

    public function getBackgroundColor(): array
    {
        return $this->backgroundColor;
    }

    public function setEncoding(string $encoding): void
    {
        $this->encoding = $encoding;
    }

    public function getEncoding(): string
    {
        return $this->encoding;
    }

    public function setRoundBlockSize(bool $roundBlockSize): void
    {
        $this->roundBlockSize = $roundBlockSize;
    }

    public function getRoundBlockSize(): bool
    {
        return $this->roundBlockSize;
    }

    public function setErrorCorrectionLevel(ErrorCorrectionLevel $errorCorrectionLevel): void
    {
        $this->errorCorrectionLevel = $errorCorrectionLevel;
    }

    public function getErrorCorrectionLevel(): ErrorCorrectionLevel
    {
        return $this->errorCorrectionLevel;
    }

    public function setLogoPath(string $logoPath): void
    {
        $logoPath = realpath($logoPath);

        if (!is_file($logoPath)) {
            throw new InvalidPathException('Invalid logo path: '.$logoPath);
        }

        $this->logoPath = $logoPath;
    }

    public function getLogoPath(): ?string
    {
        return $this->logoPath;
    }

    public function setLogoSize(int $logoWidth, int $logoHeight = null): void
    {
        $this->logoWidth = $logoWidth;
        $this->logoHeight = $logoHeight;
    }

    public function setLogoWidth(int $logoWidth): void
    {
        $this->logoWidth = $logoWidth;
    }

    public function getLogoWidth(): ?int
    {
        return $this->logoWidth;
    }

    public function setLogoHeight(int $logoHeight): void
    {
        $this->logoHeight = $logoHeight;
    }

    public function getLogoHeight(): ?int
    {
        return $this->logoHeight;
    }

    public function setLabel(string $label, int $labelFontSize = null, string $labelFontPath = null, string $labelAlignment = null, array $labelMargin = null): void
    {
        $this->label = $label;

        if (null !== $labelFontSize) {
            $this->setLabelFontSize($labelFontSize);
        }

        if (null !== $labelFontPath) {
            $this->setLabelFontPath($labelFontPath);
        }

        if (null !== $labelAlignment) {
            $this->setLabelAlignment($labelAlignment);
        }

        if (null !== $labelMargin) {
            $this->setLabelMargin($labelMargin);
        }
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabelFontSize(int $labelFontSize): void
    {
        $this->labelFontSize = $labelFontSize;
    }

    public function getLabelFontSize(): ?int
    {
        return $this->labelFontSize;
    }

    public function setLabelFontPath(string $labelFontPath): void
    {
        $resolvedLabelFontPath = realpath($labelFontPath);

        if (!is_string($resolvedLabelFontPath) || !is_file($resolvedLabelFontPath)) {
            throw new InvalidPathException('Invalid label font path: '.$labelFontPath);
        }

        $this->labelFontPath = $resolvedLabelFontPath;
    }

    public function getLabelFontPath(): ?string
    {
        return $this->labelFontPath;
    }

    public function setLabelAlignment(string $labelAlignment): void
    {
        $this->labelAlignment = new LabelAlignment($labelAlignment);
    }

    public function getLabelAlignment(): ?string
    {
        return $this->labelAlignment->getValue();
    }

    public function setLabelMargin(array $labelMargin): void
    {
        $this->labelMargin = array_merge($this->labelMargin, $labelMargin);
    }

    public function getLabelMargin(): ?array
    {
        return $this->labelMargin;
    }

    public function setWriterRegistry(WriterRegistryInterface $writerRegistry): void
    {
        $this->writerRegistry = $writerRegistry;
    }

    public function setWriter(WriterInterface $writer): void
    {
        $this->writer = $writer;
    }

    public function getWriter(string $name = null): WriterInterface
    {
        if (!is_null($name)) {
            return $this->writerRegistry->getWriter($name);
        }

        if ($this->writer instanceof WriterInterface) {
            return $this->writer;
        }

        return $this->writerRegistry->getDefaultWriter();
    }

    public function setWriterOptions(array $writerOptions): void
    {
        $this->writerOptions = $writerOptions;
    }

    public function getWriterOptions(): array
    {
        return $this->writerOptions;
    }

    private function createWriterRegistry()
    {
        $this->writerRegistry = new WriterRegistry();
        $this->writerRegistry->loadDefaultWriters();
    }

    public function setWriterByName(string $name)
    {
        $this->writer = $this->getWriter($name);
    }

    public function setWriterByPath(string $path): void
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        $this->setWriterByExtension($extension);
    }

    public function setWriterByExtension(string $extension): void
    {
        foreach ($this->writerRegistry->getWriters() as $writer) {
            if ($writer->supportsExtension($extension)) {
                $this->writer = $writer;

                return;
            }
        }

        throw new UnsupportedExtensionException('Missing writer for extension "'.$extension.'"');
    }

    public function writeString(): string
    {
        return $this->getWriter()->writeString($this);
    }

    public function writeDataUri(): string
    {
        return $this->getWriter()->writeDataUri($this);
    }

    public function writeFile(string $path): void
    {
        $this->getWriter()->writeFile($this, $path);
    }

    public function getContentType(): string
    {
        return $this->getWriter()->getContentType();
    }

    public function setValidateResult(bool $validateResult): void
    {
        $this->validateResult = $validateResult;
    }

    public function getValidateResult(): bool
    {
        return $this->validateResult;
    }

    public function getData(): array
    {
        $baconErrorCorrectionLevel = $this->errorCorrectionLevel->toBaconErrorCorrectionLevel();

        $baconQrCode = Encoder::encode($this->text, $baconErrorCorrectionLevel, $this->encoding);

        $matrix = $baconQrCode->getMatrix()->getArray()->toArray();

        foreach ($matrix as &$row) {
            $row = $row->toArray();
        }

        $data = ['matrix' => $matrix];
        $data['block_count'] = count($matrix[0]);
        $data['block_size'] = $this->size / $data['block_count'];
        if ($this->roundBlockSize) {
            $data['block_size'] = intval(floor($data['block_size']));
        }
        $data['inner_width'] = $data['block_size'] * $data['block_count'];
        $data['inner_height'] = $data['block_size'] * $data['block_count'];
        $data['outer_width'] = $this->size + 2 * $this->margin;
        $data['outer_height'] = $this->size + 2 * $this->margin;
        $data['margin_left'] = ($data['outer_width'] - $data['inner_width']) / 2;
        if ($this->roundBlockSize) {
            $data['margin_left'] = intval(floor($data['margin_left']));
        }
        $data['margin_right'] = $data['outer_width'] - $data['inner_width'] - $data['margin_left'];

        return $data;
    }
}
