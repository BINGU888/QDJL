<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/30
 * Time: 9:59
 */
// return [
//     "default_options_name"=>"alipay_options",
//     "alipay_options"=>[
//         'app_id'=>'2018092861551762',
//         //支付宝公钥
//         'alipay_public_key'=>"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAgprjn8nRwkIu6gEt6i569gzSUPaSPg1vCFP6r5zNdfgaXdSUFPiPelsUPFWUbvp6hp8gvML136rbipoRcoSWQZTe12jGk583ZP9RUQEkRz/7q+h5Bq1iFeLoEfI4Cn3EZlnrQz2bOr3BKrbPqnNo+O0iqDh1rcuvjp3AT2PH1T03KsNgIJBhVi0Mjs+7ldLrTok/qZaJQgsvZaKD3V0qHwVj/Z5Evi85w5NCg7L9zWjUNn/g7Zpo3bWcu6gmxzmnqnUVZqh66WuhvAgKiJ20TUsaSHncXJDJSyECjGyLF65Voy/RulsWWm20m1LMBBP2tQ+BbuTJM3E4W0TKE8SkNwIDAQAB",
//         'private_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA01bn6yHWSRvbWzGUSWjW
// jsXeENw1ZQt3U/ucMjgCVKe0uAKY3ucEeYFjqOQs72/OLA3ptKcUFvj4iqawHtB9
// liYTWuMdDp+BYZ1ZnRK8ypzRl3xGUw+YBFmSn2OrKGdh2CT9hMasTRW1wrv6rywc
// sbtCsSGdSKrzjfe+8pAWtYcBBypCQM0AUax9pZn8tF+/X/b7Z8aSbALGSY8NGK0Z
// aJdTFj3jApls3Mk0VJTzVO9c/aZQSXut84bfgotUF7m7lRSQP70Wwc233RpJ0tPW
// x95FccUg1ti6s+/jz6nb3EhNrQNSv3pom4bQft6xE2Ytyq3WXohPAhK56udX1yRi
// ZwIDAQAB',
//         'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuPao6KJ59fd/lQsEOdjWuA1bmH/of0t9NwQ1gr/S7TsjX1ME5RnqePZhRC5rBB4lXgXc7dK89/uchWzncKJNFJrvj50bRVJK8tRSjIain3b2r5ShYyJG28bON7mtGFNhP7dRIWnpa/wkwRNp1Vk10gU2l7tjY4JfO/0QiP+NJBluCgbuEe7XhqJFEQ6m1x0I2k4ZTSGRmHoy0bPKL+zM81E8t9Ah2/GKBje6lBX5/7yXrMVZjX+q7QDpkCc9jnQfGEZu9NeO89227wBMTxstuGaD68RbPm6XWKCDYTTUMBIT5LfQxIRcMJk89v6Bjp/+8fj/J8jZvMbzd9NQ0xUOpQIDAQAB',
//     ],

// ];
return [
		//应用ID,您的APPID。
		'app_id' => "2018092861551762",

		//商户私钥
		'merchant_private_key' => "MIIEpQIBAAKCAQEA7dQg0zEjlPQqgah4O7X7EQz6oOBsBmubfLzeyiDNSCt+Tpf17RVuIaAWPzoyBxjBhwt/0rQ8WR1I1KD6LohEpUYVBeqf0g3Df9HzePeJe37aic84gLQBvoNOsplcRfUTUF8Eyy0VSVHqFFfLLCqSLbU7E52y3f5cCmvlaXkMIHQrHuan9cd7wd5+By3jbpPyOSAZYoywA235wNz1A1PvI50vy+dZROysLdQW2jsLW3kM+h5EEOQIjP65p7J+9n70ph95eTqSg0oPyP5vFslpgJpDok5ubjq5uIVrCyCFDal7iMKqd8q4ZOv7aJiNfM4nlwpL/Cw1pmN1eIqrMNZiKwIDAQABAoIBADvJVtMgiDxawRDFJCyGi+32YE7u+9kFVhoedLdFVbWt0Vu6kcUe3Qew8cbFP2xPSd9EAccbLgyM8xEYwqvN78vIeWJ6X+DzOtE4G0eumq4j7pY0NJUOK14ULkVxjiO7/zX2HQNievZqlDi29UlODl9VLtu4ig2KcuLfytUf6++AKG920isykIrJT4Ny6A0mEig4KWMa8dHITtFoUfzsfCWfNEWU7XB4YFigEgbzsFSsvtpx1Q4Q2Tr6S8F3UdueB6Go/jigByMmdJWzZ4FbsSkH2SQh24y7BpCTZrnAnzgKE9j2ZpPr61H49ufLe6BSYqfO9br2I6GGUqJaIx5kzRECgYEA/78wwkLskvSaVrsNNpkaz3J4ksT9jiwwVUWevHTM03GAFUUwtmGDZEEXVTfw/3rgPNWFfkT+NxDPIliBxyH2cElJ40eIaFx8ElH344soqHeqRYAUW9eRHQ49eSR4kxix8lkconKFBu0KrFmQA9YGbzrbAi8DI17EIppoc828AGMCgYEA7hBlpUZiNXbbmsDs7GrYQkyrskJvX0zxxfTI2dwyx1Go2EHL1KsKK+SWrStMX3BUI60P/kf9wkKHd215W0BrkIjEnsr/MZOJBJhDVEsjqNR2khfsudaAieKUCIHl0hehEJb2I/RD4xHJVaKlGVs7GGbZqWWM6fJQzeCHuSxwbZkCgYEAkNS7TigMRaTUCq3fa74E2toewi9g0DTiIkhM/ri+jjfGq0UFsyNB/3KFPjNx/ZyehJT/BmUX+iNAyliJFjZ7k1dNyrJl44QMl2toEKpsGgu6l6VS4jgP6/52fbZTuinQDEi+2Jg2EZgH5VbLIgEAn9ka0f/BrTQrqtT6tioaf7UCgYEAlZn4RkRUswRePIHAYVFDVWaY/wyAsILJ7HxHO6EEx/yo/j1auepCGhMsHOZ4uAD+3uyCgGj9LnZgasyA1rdE4S8RYDhw5daw5BJLPU16uz3IxUYDnUUOwZeBL1dx9PSkYqh7RVzazHcA7q+m0KhkGpGHdsw6IUoFPVs770Hd5QECgYEA5j5YQiyd8w+1h+pdaopACck6oOSK2hmo9hDapP7ZjPq+2l1Ef+xii4OEb4mCpB0ZwQFZPRqpPy41MuaxWdYOVe0Er0IvbQxzxpy9muW6UYkkbB8sz0TwD6GB6k/ymFfxV+J494pkCK3ZdQP+rAyCOGvZs7fbpbYTZg4+TuXRskY=",
		
		//异步通知地址
		'notify_url' => "http://www.51qdt.com/goods/apy",
        'notify_urls'=>"http://www.51qdt.com/service/pay",
        'upgrade'=>"http://www.51qdt.com/upgrade/pay",
		'return_urls'=>"http://www.51qdt.com/bis",

		//同步跳转
		'return_url' => "http://www.51qdt.com",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAgprjn8nRwkIu6gEt6i569gzSUPaSPg1vCFP6r5zNdfgaXdSUFPiPelsUPFWUbvp6hp8gvML136rbipoRcoSWQZTe12jGk583ZP9RUQEkRz/7q+h5Bq1iFeLoEfI4Cn3EZlnrQz2bOr3BKrbPqnNo+O0iqDh1rcuvjp3AT2PH1T03KsNgIJBhVi0Mjs+7ldLrTok/qZaJQgsvZaKD3V0qHwVj/Z5Evi85w5NCg7L9zWjUNn/g7Zpo3bWcu6gmxzmnqnUVZqh66WuhvAgKiJ20TUsaSHncXJDJSyECjGyLF65Voy/RulsWWm20m1LMBBP2tQ+BbuTJM3E4W0TKE8SkNwIDAQAB",
];