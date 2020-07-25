<?php  

$mypost = '[
{
	"username": "relaxing.media"
},
{
	"username": "relaxing.media"
},
{
	"username": "relaxing.media"
},    
{
	"username": "faanteyki"
},
{
	"username": "faanteyki"
},
{
	"username": "faanteyki"
},
{
	"username": "faanteyki"
},
{
	"username": "iauyulstr"
},
{
	"username": "iauyulstr"
},
{
	"username": "iauyulstr"
},
{
	"username": "iauyulstr"
},
{
	"username": "iauyulstr"
},
{
	"username": "iauyulstr"
},
{
	"username": "iauyulstr"
},
{
	"username": "iauyulstr"
},
{
	"username": "iauyulstr"
} 
]';

// Basic sample data.
$players = json_decode($mypost,true);


// Make a matrix. 2d array with a column per group.
$matrix = array_chunk($players, ceil(count($players)/4));

// echo json_encode($matrix,JSON_PRETTY_PRINT);
// exit;

// Reverse every other row.
for ($i = 0; $i < count($matrix); $i++) {
	$matrix[$i] = array_reverse($matrix[$i]);
	// if ($i % 4) {
	// }
}

// echo json_encode($matrix,JSON_PRETTY_PRINT);
// exit;


$matrix = '[
[
{
	"id": "2360256147816916827",
	"username": "relaxing.media",
	"code": "CDBUHYVjydb",
	"url": "https:\/\/www.instagram.com\/p\/CDBUHYVjydb\/",
	"type": "image",
	"thumbnail": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/116236803_308108857022865_7358345981062245593_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=109&_nc_ohc=Wvyj6OYFHYQAX8D8TOA&oh=00e8062ff93eadc773dd147bca554fc9&oe=5F465274",
	"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/116236803_308108857022865_7358345981062245593_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=109&_nc_ohc=Wvyj6OYFHYQAX8D8TOA&oh=00e8062ff93eadc773dd147bca554fc9&oe=5F465274",
	"caption": "Test caption\n\n#king\n#brrak\n#kongkow",
	"haslike": false
},
{
	"id": "2359020850135201042",
	"username": "relaxing.media",
	"code": "CC87PbuDHUS",
	"url": "https:\/\/www.instagram.com\/p\/CC87PbuDHUS\/",
	"type": "video",
	"thumbnail": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/109956723_2195641653914746_4928919953309775301_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=110&_nc_ohc=J2Nntiw8BPQAX9bVhqC&oh=2641e68956cf09ec522bb588313cf9ee&oe=5F1E0375",
	"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t50.2886-16\/115616043_619098452052623_4788103679555559210_n.mp4?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=111&_nc_ohc=R_9qyPo1DL4AX8owaW7&oe=5F1E3EC3&oh=5ae242c3a2a1bdf854276e01d76911e4",
	"caption": "Dari seseorang  ntah siapa namanya...",
	"haslike": false
},
{
	"id": "2337474056603031309",
	"username": "relaxing.media",
	"code": "CBwYEUfg9cN",
	"url": "https:\/\/www.instagram.com\/p\/CBwYEUfg9cN\/",
	"type": "image",
	"thumbnail": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/105458153_316913635992874_4241971381516797673_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=110&_nc_ohc=4kgmmJWy3MMAX9SkvA_&oh=098ff412c1b22269af4d99e8236c7b41&oe=5F472C83",
	"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/105458153_316913635992874_4241971381516797673_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=110&_nc_ohc=4kgmmJWy3MMAX9SkvA_&oh=098ff412c1b22269af4d99e8236c7b41&oe=5F472C83",
	"caption": false,
	"haslike": false
},
{
	"id": "2332273850542254251",
	"username": "relaxing.media",
	"code": "CBd5rXMgOir",
	"url": "https:\/\/www.instagram.com\/p\/CBd5rXMgOir\/",
	"type": "video",
	"thumbnail": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/103344707_283962229639256_7495033701306523003_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=108&_nc_ohc=fLJQcPZ-jK4AX_RHFHZ&oh=50897b360cee2890885b5cf632a07c2a&oe=5F1E3994",
	"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t50.2886-16\/102857774_1631334917030721_4033998515988916019_n.mp4?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=109&_nc_ohc=6RpVngks-lkAX-0d5ck&oe=5F1DE2CE&oh=0e3171b02894cf380ca9dae85ba669cc",
	"caption": "\u66f2\u82b7\u542b\uff0c\u5929\u7a7a\u597d\u50cf\u4e0b\u96e8\nhttps:\/\/www.bilibili.com\/video\/BV1xs411E7ek",
	"haslike": false
}
],
[
{
	"id": "2337475112185728353",
	"username": "faanteyki",
	"code": "CBwYTrlJY1h",
	"url": "https:\/\/www.instagram.com\/p\/CBwYTrlJY1h\/",
	"type": "image",
	"thumbnail": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/105041256_353750655590942_4596889337450547076_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=102&_nc_ohc=p46-UcRMY68AX8ccyz4&oh=96dc6bf0a76cbbd96c4e6d5a55fbf6cc&oe=5F4438C1",
	"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/105041256_353750655590942_4596889337450547076_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=102&_nc_ohc=p46-UcRMY68AX8ccyz4&oh=96dc6bf0a76cbbd96c4e6d5a55fbf6cc&oe=5F4438C1",
	"caption": false,
	"haslike": false
},
{
	"id": "2337450133142497387",
	"username": "faanteyki",
	"code": "CBwSoMCJehr",
	"url": "https:\/\/www.instagram.com\/p\/CBwSoMCJehr\/",
	"type": "image",
	"thumbnail": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/104767299_2303808766581205_7129864476741949163_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=101&_nc_ohc=exH7uK7HTuAAX8Ksq1t&oh=205f3a39305b2beb5929cd2b5c77345f&oe=5F46CE7C",
	"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/104767299_2303808766581205_7129864476741949163_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=101&_nc_ohc=exH7uK7HTuAAX8Ksq1t&oh=205f3a39305b2beb5929cd2b5c77345f&oe=5F46CE7C",
	"caption": false,
	"haslike": false
},
{
	"id": "2261685801469848638",
	"username": "faanteyki",
	"code": "B9jH0DmJ6w-",
	"url": "https:\/\/www.instagram.com\/p\/B9jH0DmJ6w-\/",
	"type": "image",
	"thumbnail": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/89376883_194939165120530_3056267631883614224_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=110&_nc_ohc=G-AXJ7NVopkAX-LaxVi&oh=e16ea9fdd0fe5b444fa5a1927e07fedc&oe=5F46C074",
	"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/89376883_194939165120530_3056267631883614224_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=110&_nc_ohc=G-AXJ7NVopkAX-LaxVi&oh=e16ea9fdd0fe5b444fa5a1927e07fedc&oe=5F46C074",
	"caption": "Wwow",
	"haslike": false
}
],
[
{
	"id": "2356446434853063862",
	"username": "iayulstr",
	"code": "CCzx4wppwy2",
	"url": "https:\/\/www.instagram.com\/p\/CCzx4wppwy2\/",
	"type": "carousel",
	"thumbnail": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/109213920_286411479296239_2409966763171766236_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=107&_nc_ohc=RF9n-BMG1ccAX9iWz08&oh=a6d2f8cb1019b4c3ed1424c58db590ec&oe=5F463D91",
	"media": [
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/109213920_286411479296239_2409966763171766236_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=107&_nc_ohc=RF9n-BMG1ccAX9iWz08&oh=a6d2f8cb1019b4c3ed1424c58db590ec&oe=5F463D91",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/109692542_634468590505115_8627190872033332317_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=111&_nc_ohc=eJua6xVFnWYAX-ffk6J&oh=ef9e90cd01ce75b6dbe9a1dff275ccbb&oe=5F44689E",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/109953855_2704847583083814_5293935330143226284_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=106&_nc_ohc=3KHFfe3t_jwAX9ll-hP&oh=d90a27835c16e956870352c5275cf4a5&oe=5F44AE61",
		"type": "image"
	}
	],
	"caption": false,
	"haslike": false
},
{
	"id": "2341284483212339211",
	"username": "iayulstr",
	"code": "CB96dU0AWgL",
	"url": "https:\/\/www.instagram.com\/p\/CB96dU0AWgL\/",
	"type": "carousel",
	"thumbnail": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/105560981_270135621085863_3211545785314645782_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=103&_nc_ohc=TOcZmxcWyc0AX_yP4GD&oh=fd3b299c7850cedc46c2083edbf36050&oe=5F44FED9",
	"media": [
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/105560981_270135621085863_3211545785314645782_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=103&_nc_ohc=TOcZmxcWyc0AX_yP4GD&oh=fd3b299c7850cedc46c2083edbf36050&oe=5F44FED9",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/105974835_112840760318040_4353680326853013792_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=100&_nc_ohc=khcYZMbPo24AX_doDRM&oh=02735e0f51199ec2cde5b02edf41c26b&oe=5F45898D",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/105976031_1022297278222297_5012566505674950961_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=101&_nc_ohc=hACLwhyE5TIAX-XoEOF&oh=fd82f8971a21ddc5b04c78a0be5a90a6&oe=5F4391D8",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/105941095_321828622157412_8809552703463001525_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=100&_nc_ohc=FtjsAW4VfxEAX-J7Mlc&oh=0ec5aa4641803f27bcbb66e842e6583e&oe=5F46CD5D",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/105939492_870030743482772_7148357388743807374_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=108&_nc_ohc=pZ_ZHbvlGqwAX8GPQ0k&oh=b8a1e462c01fedee5eaf3190d9522d6d&oe=5F451675",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/106596912_924201408006306_7657477307674708390_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=106&_nc_ohc=ORuWuGJAPgkAX9cs4HD&oh=76b0fbf04ea7d9e01b799a93f69df0a1&oe=5F44389C",
		"type": "image"
	}
	],
	"caption": "Green day\ud83c\udf31",
	"haslike": false
},
{
	"id": "2334281408218311186",
	"username": "iayulstr",
	"code": "CBlCJK-pqoS",
	"url": "https:\/\/www.instagram.com\/p\/CBlCJK-pqoS\/",
	"type": "carousel",
	"thumbnail": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/104094861_593867164874953_4054093600670470807_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=101&_nc_ohc=Seb5Z50-VRgAX-tKky9&oh=01c85b41bb913c23f1277c2093d7f7d3&oe=5F1E436D",
	"media": [
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t50.2886-16\/104339155_273746073742117_7033513501107473533_n.mp4?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=101&_nc_ohc=ZQz-kYEnyAwAX-wPgSz&oe=5F1E10B8&oh=295516c3825dde19186b95a9f6562a1f",
		"type": "video"
	},
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/104219959_308184296862375_9078975739818173827_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=103&_nc_ohc=yAKs6LpHu6QAX9rnWHi&oh=a42a96aae3d8f4a5b4de46ee93475059&oe=5F4697C5",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/103977465_876938346145258_3144809334778514105_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=110&_nc_ohc=K3aqi9_VK-AAX8X4LqY&oh=16f1e67901db5dd947e55a7ad484cb0b&oe=5F449330",
		"type": "image"
	}
	],
	"caption": "Maap emang edit nya di tiktok \ud83d\ude42 (gasi sebenernya sampe 3 apk edit wkwk)\nMencoba ikutin #barbiechallenge ini,tapi keknya fail\ud83d\ude06 \nyaudah gpp lah buat seru-seruan\ud83d\ude02\n\nInspired by : @jharnabhagwani",
	"haslike": false
},
{
	"id": "2321236259831358017",
	"username": "iayulstr",
	"code": "CA2sBRzASJB",
	"url": "https:\/\/www.instagram.com\/p\/CA2sBRzASJB\/",
	"type": "image",
	"thumbnail": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/101447689_2607042099583368_7051111474820357272_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=100&_nc_ohc=xMVGfuccHlQAX95z2gT&oh=fc0520ddf5c6d40a83be10b5af022512&oe=5F43616B",
	"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/101447689_2607042099583368_7051111474820357272_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=100&_nc_ohc=xMVGfuccHlQAX95z2gT&oh=fc0520ddf5c6d40a83be10b5af022512&oe=5F43616B",
	"caption": "Aloha (:\nGa,ini bukan gue bukaaannnnnnnnnn aaarggghhhh mengapa epek mengubah segalanya\ud83d\ude2d",
	"haslike": false
},
{
	"id": "2310703836314433712",
	"username": "iayulstr",
	"code": "CARROWAp3iw",
	"url": "https:\/\/www.instagram.com\/p\/CARROWAp3iw\/",
	"type": "carousel",
	"thumbnail": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/97285955_246080279941855_2826370016696718867_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=107&_nc_ohc=XOVObRqHtlcAX9ee0Sc&oh=569cca5710ee99eeaf230ed03b6e825e&oe=5F435C09",
	"media": [
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/97285955_246080279941855_2826370016696718867_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=107&_nc_ohc=XOVObRqHtlcAX9ee0Sc&oh=569cca5710ee99eeaf230ed03b6e825e&oe=5F435C09",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/97359281_246068096836441_4467785185345308994_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=101&_nc_ohc=cZivrynm52UAX8FyxHs&oh=7cefee5c31ff01849a15c12029837253&oe=5F46BA5F",
		"type": "image"
	}
	],
	"caption": "Gada bedanya cuma beda di filter\ud83d\ude02\n\n#pink \n#onfleek",
	"haslike": false
},
{
	"id": "2299301130232388390",
	"username": "iayulstr",
	"code": "B_owjIbJ9sm",
	"url": "https:\/\/www.instagram.com\/p\/B_owjIbJ9sm\/",
	"type": "video",
	"thumbnail": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/95261797_1004681536592598_7417685505429779661_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=106&_nc_ohc=BQOnt0i0H7cAX9sv9xR&oh=402c2eeb3c30d1230a10cdf8f3f49a58&oe=5F1E1072",
	"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t50.2886-16\/95163342_1186195018395931_6078882869667647937_n.mp4?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=107&_nc_ohc=mqQ_ydXRgVcAX-xZDCz&oe=5F1DB82F&oh=f0604115c452bf9331e818cbc961d2de",
	"caption": "Udah lama mau bikin video kek gini,tapi baru sempet\ud83d\ude42 gapapa lah ya ga bagus\u00b2 amat.. bodoamat deh alis gue deh terserah alisnya deh wkwk dan udah pake maskara juga ga keliatan bulu matanya naek,sebel\ud83e\udd7a..\nBtw,mon maap w pake mukena soalnya kerudungan gue yg ndemplek(?) dimuka lagi di cuci (: #makeuplook \n#solo",
	"haslike": false
},
{
	"id": "2289880932869785654",
	"username": "iayulstr",
	"code": "B_HSpN9pMg2",
	"url": "https:\/\/www.instagram.com\/p\/B_HSpN9pMg2\/",
	"type": "video",
	"thumbnail": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/93807768_525252161498712_6034938487522996616_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=100&_nc_ohc=bVzUOlNybzYAX9VJl8v&oh=1522c6ba0a101caef4d1218fa54fd176&oe=5F1E388A",
	"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t50.2886-16\/93979008_155005095912678_5214650311449481286_n.mp4?efg=eyJ2ZW5jb2RlX3RhZyI6InZ0c192b2RfdXJsZ2VuLjU3Ni5mZWVkLmRlZmF1bHQiLCJxZV9ncm91cHMiOiJbXCJpZ193ZWJfZGVsaXZlcnlfdnRzX290ZlwiXSJ9&_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=106&_nc_ohc=mcpBl0C5NN0AX93OxtP&vs=18096711892178272_450258114&_nc_vs=HBksFQAYJEdJQUJtZ1htSks3dl9Zd0FBRWFRMDNIVUtGNUlia1lMQUFBRhUAAsgBABUAGCRHTFR3bHdYdERxelg0UlVEQUhnSVhwMER1dzlLYmtZTEFBQUYVAgLIAQAoABgAGwGIB3VzZV9vaWwBMBUAABgAFsDAh5Sit6VAFQIoAkMzLBdAOTMzMzMzMxgSZGFzaF9iYXNlbGluZV8xX3YxEQB16gcA&_nc_rid=7d23b3fb76&oe=5F1DDF35&oh=a6630bdd529c1303d853c2b22729b6ab",
	"caption": "#passthebrushchallenge \nLalalalala lonely~\n\nIn frame (@adheliapuspita, @deskaputrikurnia,@dwitifanyfadiat)",
	"haslike": false
},
{
	"id": "2289161617583335087",
	"username": "iayulstr",
	"code": "B_EvFzZg2av",
	"url": "https:\/\/www.instagram.com\/p\/B_EvFzZg2av\/",
	"type": "carousel",
	"thumbnail": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/93491909_159280612237517_6984730623236006180_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=105&_nc_ohc=pEe1Hmn95NYAX8isv_1&oh=4aae8359939b962b1ffcd84b43490603&oe=5F46699D",
	"media": [
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/93491909_159280612237517_6984730623236006180_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=105&_nc_ohc=pEe1Hmn95NYAX8isv_1&oh=4aae8359939b962b1ffcd84b43490603&oe=5F46699D",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/92926914_128793892059657_2904196917482822870_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=109&_nc_ohc=Lxhig74rEEgAX_-oyGi&oh=609a7ed98706af7024c6e3a6fed8a909&oe=5F44A81E",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/93458428_148123423394886_3216018051500382401_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=106&_nc_ohc=qPVWAA0fKjMAX-fU9Wo&oh=347c10580a512d46d6dbee6e1fa57c4f&oe=5F449049",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/93359070_718568792220525_8248646195664670680_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=109&_nc_ohc=JCx-BxJluAYAX9VV7i6&oh=ed2d4971dc7ff8ccdc889d5b5e26830a&oe=5F438AED",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/93223738_531976347357490_4047307059312061664_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=110&_nc_ohc=Bhmog8QqnHcAX_jggkI&oh=a0d3db78f3bfa3fef3d5354713087b63&oe=5F4355C4",
		"type": "image"
	}
	],
	"caption": "Feel like Jang man wol\ud83d\ude06\nBtw,karna kusuka sekali warna biru jadi kupakai black pink\ud83d\ude02\nSlide ke-3 napa tegang amat ya\ud83d\ude2d\ud83e\udd23 Oiya,dress cantik ini ku beli di @dexalove_ dan sendalnya pun di @dexalove.stuff \u263a\ufe0f",
	"haslike": false
},
{
	"id": "2255113974668748019",
	"username": "iayulstr",
	"code": "B9LxjY8JmTz",
	"url": "https:\/\/www.instagram.com\/p\/B9LxjY8JmTz\/",
	"type": "carousel",
	"thumbnail": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/83773348_2623427234452424_6082701285481803298_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=108&_nc_ohc=_RyfMqC3AmEAX_HKyxT&oh=ad777f1b1115b8ea9f4a66b6369f6e7b&oe=5F471A50",
	"media": [
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/83773348_2623427234452424_6082701285481803298_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=108&_nc_ohc=_RyfMqC3AmEAX_HKyxT&oh=ad777f1b1115b8ea9f4a66b6369f6e7b&oe=5F471A50",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/84331784_497670077607576_1303093417882606108_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=100&_nc_ohc=lPmg1q6xT6oAX-8Za2h&oh=ae3adfb3c0f7a837c4d1c53679c1b605&oe=5F44E764",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/88183159_141028737392512_6224917755102469483_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=104&_nc_ohc=EYDSUBhJbRoAX9go3uR&oh=f7416ecf343210ef2dfaf714a65f6bb0&oe=5F44E7C4",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/87556213_510883673165182_3877982452694222910_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=110&_nc_ohc=ni09xbATZhQAX-rC20V&oh=c420c1aecfb3e2755aae7cf7c8b8ddae&oe=5F441052",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/87637748_221857472196258_7621615577955353500_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=106&_nc_ohc=fH8t-Lh3M78AX8lGJSC&oh=86fb63550661c8a1f5dea0ae637f99f5&oe=5F46E7C9",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/87653004_195477291862730_1404499157205643176_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=102&_nc_ohc=H9brLScJYSkAX9sVz5p&oh=272353b7b85c488e65e88a81f7c68a3d&oe=5F45A267",
		"type": "image"
	}
	],
	"caption": false,
	"haslike": true
},
{
	"id": "2211666539248185217",
	"username": "iayulstr",
	"code": "B6xav_hgW-B",
	"url": "https:\/\/www.instagram.com\/p\/B6xav_hgW-B\/",
	"type": "carousel",
	"thumbnail": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/81298969_1803675543096012_482604749001237293_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=100&_nc_ohc=ahacxZNB99wAX86BxgS&oh=3660900117afb8a962b9e9b50f242396&oe=5F4726F9",
	"media": [
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/81298969_1803675543096012_482604749001237293_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=100&_nc_ohc=ahacxZNB99wAX86BxgS&oh=3660900117afb8a962b9e9b50f242396&oe=5F4726F9",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/80584749_115840523011956_5459364412002286881_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=100&_nc_ohc=-vniekSEbxIAX9GOqSD&oh=e60b5b5fed55f796978e1bf89a04406b&oe=5F435FA5",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/79376596_110992190255001_893434570166957309_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=109&_nc_ohc=3Sv6eIj5buIAX_tzUWM&oh=d5aeec16192480bc8b199887f00400b2&oe=5F463DEE",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/79250162_164924198194922_8021875647449099300_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=102&_nc_ohc=Vt-dhIKGHuwAX_zwZfJ&oh=d727a8f71d1b6f185cc7477a8380209c&oe=5F434AF0",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/79529234_1004897503227822_8796232493322391880_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=106&_nc_ohc=9xYQOdflvYoAX_rFxkp&oh=1827a78caf7d04b983b2c419029ee554&oe=5F45DE03",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/79601208_226755108337297_5223359376553283361_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=101&_nc_ohc=lQj-C7CY3h0AX_V28VF&oh=55106db441186a63f266bbe4449d31ab&oe=5F46988D",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/79461347_2498670376869028_1587757089947774750_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=104&_nc_ohc=1FyeuqLft68AX8PcupB&oh=d5dcfda96c426517c56f6d0e38051eb1&oe=5F45796C",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/81751435_452336132322969_1586761964076379645_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=109&_nc_ohc=XuNbkVpAzBkAX_g1wNm&oh=45c55d9c033bc2785516f2671d08d01b&oe=5F461D2F",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/81467573_545215996063315_1098266249886642476_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=107&_nc_ohc=OOjFBSPE9dwAX_zg7c3&oh=3e3d6502c8b96553e146ba61ef19404b&oe=5F443147",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/79032451_196100151438866_8347771614196209451_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=102&_nc_ohc=rmqThin-GWAAX92vrN5&oh=04304148d04f27151fb0e0f5a9e0fa26&oe=5F45CE66",
		"type": "image"
	}
	],
	"caption": "#2020 \nYaudah cuma hesteg doang , mang napasi gaboleh?!\ud83e\udd2a .\n.\n.\n.\n.\n.\nIn frame : @dsimlni , @sevivera_11 .\n.\n.\n.\n\ud83d\udcf8 : thor",
	"haslike": false
},
{
	"id": "2209601730988449501",
	"username": "iayulstr",
	"code": "B6qFRE-p57d",
	"url": "https:\/\/www.instagram.com\/p\/B6qFRE-p57d\/",
	"type": "carousel",
	"thumbnail": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/79506443_171393747282455_7183867246753777840_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=111&_nc_ohc=3BHOOcWPe9EAX_4_ZQB&oh=f505d471b51f32eaec230e8baf3ffb67&oe=5F451A5D",
	"media": [
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/79506443_171393747282455_7183867246753777840_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=111&_nc_ohc=3BHOOcWPe9EAX_4_ZQB&oh=f505d471b51f32eaec230e8baf3ffb67&oe=5F451A5D",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/79176199_301874684060181_1644888514681286251_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=109&_nc_ohc=2PqTciEAe9QAX_9tRcz&oh=9f7e6bc96fcf3c3554057e8adebfa932&oe=5F470D42",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/79377114_512122392986018_3874013685866938402_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=102&_nc_ohc=_6IRmW9T3WMAX8NPJr8&oh=b461471203682407de42d32edc941f7c&oe=5F446E03",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/81477345_1348916751954846_6162325755420000704_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=106&_nc_ohc=o50TwJzy0HYAX-fYSvh&oh=ec872c60371a78ef2873afd4eb628143&oe=5F461B9B",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/76993872_489948504990485_7722871281529488312_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=105&_nc_ohc=tSX1bXt7bd4AX-GZmpz&oh=a37ea58ad456f252abd87433cc72fcbe&oe=5F45BDED",
		"type": "image"
	}
	],
	"caption": "Wedding day nya mba kuh @auliaiza77,wedding ini mempertemukanku kepada mereka yg sekian lama tidak bertemu wkwk @sevivera_11 dan @dsimlni \n#wedding \n#sundayfunday",
	"haslike": false
},
{
	"id": "2202397289402924703",
	"username": "iayulstr",
	"code": "B6QfKp5JP6f",
	"url": "https:\/\/www.instagram.com\/p\/B6QfKp5JP6f\/",
	"type": "carousel",
	"thumbnail": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/78856792_115851916571853_8865372151554277844_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=109&_nc_ohc=ni804ubqLAEAX9rSUMM&oh=aa889471f8f7a25bef481ea88e6a971d&oe=5F46A2CA",
	"media": [
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/78856792_115851916571853_8865372151554277844_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=109&_nc_ohc=ni804ubqLAEAX9rSUMM&oh=aa889471f8f7a25bef481ea88e6a971d&oe=5F46A2CA",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t51.2885-15\/e35\/75566987_104433227662317_2923980813579546019_n.jpg?_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=109&_nc_ohc=L37sWNcAqfMAX8zZumI&oh=8d19aa252c841447cb648b1f249db63d&oe=5F4446F0",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-2.fna.fbcdn.net\/v\/t51.2885-15\/e35\/80788230_192846335180994_5388735902830398168_n.jpg?_nc_ht=instagram.fcgk18-2.fna.fbcdn.net&_nc_cat=106&_nc_ohc=tlmLSrFT3d8AX__fUIJ&oh=08646c68012aaee1449ea76b5f0fb6d2&oe=5F44555F",
		"type": "image"
	},
	{
		"media": "https:\/\/instagram.fcgk18-1.fna.fbcdn.net\/v\/t50.2886-16\/81437358_576604326244707_6275297944003130923_n.mp4?efg=eyJ2ZW5jb2RlX3RhZyI6InZ0c192b2RfdXJsZ2VuLjcyMC5jYXJvdXNlbF9pdGVtLmRlZmF1bHQiLCJxZV9ncm91cHMiOiJbXCJpZ193ZWJfZGVsaXZlcnlfdnRzX290ZlwiXSJ9&_nc_ht=instagram.fcgk18-1.fna.fbcdn.net&_nc_cat=103&_nc_ohc=Z6wBR_jm4ggAX_8FGFL&vs=17857631407658665_1409503867&_nc_vs=HBkcFQAYJEdLNmkyZ1Jqc1Fvb2F3d0NBQ3UyQzJ3N1ZoWlhia1lMQUFBRhUAAsgBACgAGAAbAYgHdXNlX29pbAEwFQAAGAAW0paA5%2FnauD8VAigCQzMsF0AXEGJN0vGqGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHXuBwA%3D&_nc_rid=7d23b785d1&oe=5F1E126F&oh=afb888ffb24af04e26838f35869378f5",
		"type": "video"
	}
	],
	"caption": "Idk but,gue pen posting\ud83d\ude1b\nBtw. Hello December!",
	"haslike": false
}
]
]';

$matrix = json_decode($matrix);

// Flip the matrix.
$groups = array_map(null, ...$matrix); // PHP 5.6 with the fancy splat operator.
//$groups = call_user_func_array('array_map', array_merge([null], $matrix)); // PHP < 5.6 - less fancy.

$out = array();
foreach($groups as $arr)
{
	foreach($arr as $key => $val){    	
		if ($val) {
			$out[] = $val;
		}
	}
}

echo json_encode($out,JSON_PRETTY_PRINT);
exit;

// The result is...
// print_r($groups);