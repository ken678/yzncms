<?php

return array (
  0 => 
  array (
    'name' => 'paytypelist',
    'title' => '支付方式',
    'type' => 'checkbox',
    'options' => 
    array (
      'wechat' => '微信支付',
      'alipay' => '支付宝支付',
    ),
    'value' => 'wechat,alipay',
  ),
  1 => 
  array (
    'name' => 'wechat',
    'title' => '微信',
    'type' => 'array',
    'value' => 
    array (
      'app_id' => '',
      'app_secret' => '',
      'mch_id' => '',
      'key' => '',
      'mode' => '0',
      'log' => '1',
    ),
    'tip' => '微信参数配置',
  ),
  2 => 
  array (
    'name' => 'alipay',
    'title' => '支付宝',
    'type' => 'array',
    'value' => 
    array (
      'app_id' => '2017032306368806',
      'private_key' => 'MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCwnxrMgfZttHK2OHZ1kM9ITnHT+Me/okTwGUgxCF5T/K1h/wC3DqSU87C1petS8UcM3hzmWrqoWzbrPmu0D9zFzJXnEWb6M34/0iQoX9Kk9MgsDPi4C4yOz+CKwQishaMl90IoWoydzbxKiyRwpYnwC2u9XumVghi/xYhXCq3Kqp05NJNatkXP/5qGngbCmcGVcSdZGNAMbjLO6E2y+DAGmQvR3xGa77hYdE0V57aFwYFDfybWyT5S//p3CJ9K1806/IRpNJlIBUc1GqLSqUPgPyXqxAC4YLBLrxtSkyb1FAJJMJ370T7+pAlPfzkAb55/5R0hAav8TSyy+CU3TpeJAgMBAAECggEBAKgYNOQwSH2ugIJmiG9py61wYysvmcQLxvqPxUJNSaE7WTRsAp02RXMx3pty9t1wnoNjnTOrY/y5GzKWrOCpPB/Qj1ZkHJLBkUViVWFLmWm/QgeSrOBUtYhx1a2Y/A1d/qEyivdm/m9tj+eON7cyBW69H+QSQdM2ByH0+MaGdlLrqQ1Ij+tnlAugLrljHc/AXbWRBy/ZcJKxEkcHmdBGAuHj9xwrI6SY7mWUTAY4i7mqmrzOWrf049yFBveFomRdqYKf5ZyCm5mj/ZHKCknYkmZA9MOZdL1q1aCit+zUYlJffvQmJl8NWpqZARr5HmSVwG+ojqQxSCZc4wMdggbsawECgYEA7YZ0DZUNp9sTge8ewDxA75P+vxo4uC2TwWdN3MgD5RuhQPjUBOIJ2ymt+OK97XDqVpUS8z+8FGmG4lctl5ALGx8PwarwQdzzOJ+IR5qJZL5XY40G47pi5wpSa3BwsyeNFfqtDGhwUgSPGBqFt3LbXzSw+urVywRsVt/sGNuDWGECgYEAvlvzcK4W6T3mjLaqiulu5ljXeTakwlkMlflVHYWwSHjY7o0JuH1ujx7D067BP4cWabbPwiL4UH258eYBrVCvjjDZ6JRYJginfKa4ox6ZpXimKeIwYM346gOkyBWaC0MfCWqHnQezaodiLtmgpUY6iNRczWRwoAS/05y2MGJ7cCkCgYAHaWOjrNwBOd/Q9SIzg23PTAHt+qZ8cMekiFGwqvkcJBGTEnCjOl5uXU2Vb5XXm+dkDkDKIFoBFQ3lcCz3BsKh9QUr2OaoV3mrcabx/Qt+fkYB0Du/zWgap4LM4XRS0tAfOHQ/595hYM0KRoGXqNjZjq5bFgrXy+qJh3E5kVf24QKBgGrdxDUmBsa6cYvdoLCLjC7VAWKTQru4slAfwaDpRKXq8h46H5LrF/5RDy7vlJ3NgM+oScp7R1wouRMMRTS/b08sLalcXoA7q4tXLaCltSwoJ2CsEuzFtU/q679IIUrhS7NFkwydHiRYDM6wbndajdHzJoZINNIg2HA8MivHnfTBAoGBALp/Xch8C00uTdsfwIBVzxloPL3zVcFCrK4FxHtmrFn8HcYaPJ7D4Ny7Lcg65PtKnMhapT6pD0R1NaUPjmpAPg3V0/PFsMWKlTD+Od53uyvVF4mlP8KAVk2xsTyHQkjADMOTZPFze/ZLZxTu+2kFyuJchWsiyR/3+b3lZ8Le08lZ',
      'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA8cZs7qYjjuRneKQ9Lly4PltHo7K751+UPIFWcCuoAPac/duwj1kL/DnB+Y2Lj4zhd6CvIKJJRSwmr92fbE2fEvOVV9Nrz4K5txxUnP13nHKk9WSxO7usvpOUw+gMZa1w5Y7EMmVgWU2LNaVjCnotExocoOK6CiW+WoQSYKbLr2y+l1OpS6Q+5XaCBr2U/WxT7N4ITUNDg1vH3GC1Z7iAfHik3M/p1a3fmSlz3orQNf7JX5EW70nmt8LPfd4/VT1MdRbwVRLkxzLXyYYdseE0M65z0yl9lFE2PXfpJcJQ0palADHo+8+h6Q9szvzhGGcwi9VH3FmXEV3AFKeuJQwaGwIDAQAB',
      'mode' => '0',
      'isper' => '1',
      'log' => '1',
    ),
    'tip' => '支付宝参数配置',
  ),
);
