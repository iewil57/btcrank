<?php
error_reporting(0);
const host = "btcrank.co",
b = "\033[1;34m",
c = "\033[1;36m",
d = "\033[0m",
h = "\033[1;32m",
k = "\033[1;33m",
m = "\033[1;31m",
n = "\n",
p = "\033[1;37m",
u = "\033[1;35m";

//CLASS MODUL
function Run($u, $h = 0, $p = 0, $m = 0,$c = 0,$x = 0){//url,header,post,proxy
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $u);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_COOKIE,TRUE);
	if($c){
		curl_setopt($ch, CURLOPT_COOKIEFILE,"cookie.txt");
		curl_setopt($ch, CURLOPT_COOKIEJAR,"cookie.txt");
	}
	if($p){
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
	}
	if($h){
		curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
	}
	if($m){
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $m);
	}
	if($x){
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
		curl_setopt($ch, CURLOPT_PROXY, $x);
	}
	curl_setopt($ch, CURLOPT_HEADER, true);
	$r = curl_exec($ch);
	$c = curl_getinfo($ch);
	if(!$c) return "Curl Error : ".curl_error($ch); else{
		$hd = substr($r, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		$bd = substr($r, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		curl_close($ch);
		return array($hd,$bd)[1];
	}
}
function bn(){
	system('clear');
	print "\n\n";
	print h."Author  : ".k."iewil".n;
	print h."Script  : ".k.host.n;
	print h."Youtube : ".k."youtube.com/c/iewil".n;
	print line();
}
function Line(){$l = 50;return b.str_repeat('─',$l).n;}
function Tmr($tmr){$timr=time()+$tmr;while(true){echo "\r                       \r";$res=$timr-time(); if($res < 1){break;}echo date('i:s',$res);sleep(1);}}
function Save($namadata){
	if(file_exists($namadata)){$datauser=file_get_contents($namadata);}else{$datauser=readline(h."Input ".$namadata.p.' ≽'.n);file_put_contents($namadata,$datauser);}
	return $datauser;
}
function dash($ua){
	$r = Run('https://'.host.'/dashboard',$ua);
	$u = explode('</h5>',explode('<h5 class="font-size-15 text-truncate">',$r)[1])[0];//iewilmaestro
	$b = explode('</h4>',explode('<h4 class="mb-0">',explode('<p class="text-muted font-weight-medium">Balance</p>',$r)[1])[1])[0];
	return ["user"=>$u,"bal"=>$b];
}

system("termux-open-url  https://www.youtube.com/c/iewil");

bn();
cookie:
//$user_agent=Save('User_Agent');
//$cookie=Save('Cookie');
//$em=Save('Email/wallet faucetpay');
$em = "purna.iera@gmail.com";
$user_agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.82 Safari/537.36";
$cookie="csrf_cookie_name=1da402ade2ae90264e0faf9df9874b5e; ci_session=51ff825f5611cd46a35c490bf865dd77466c9fcc";
bn();
$ua[]="cookie: ".$cookie;
$ua[]="user-agent: ".$user_agent;

$r = dash($ua);
echo h."Username   ~> ".k.$r["user"].n;
echo h."Balance    ~> ".k.$r["bal"].n;
print Line();

menu:
echo m."1 >".p." Auto Faucet\n";
echo m."2 >".p." Withdraw\n";
echo m."3 >".p." Update Cookie\n";
$pil = readline(h."Input Number ".m."> ");
print line();
if($pil==1){goto auto;
}elseif($pil==2){goto wd;
}elseif($pil==3){unlink("Cookie");goto cookie;
}else{echo m."Bad Number\n".n;print l();goto menu;}


auto:
while(true){
	$r = Run('https://'.host.'/auto',$ua);
	$t = explode(",",explode('let timer = ',$r)[1])[0];//180,
	if($t){tmr($t);}
	$token = explode('">',explode('<input type="hidden" name="token" value="',$r)[1])[0];

	$data = "token=".$token;
	$bal1 = dash($ua)["bal"];
	
	$r2 = Run('https://'.host.'/auto/verify',$ua,$data);
	$ss = explode(' token',explode("Swal.fire('Good job!', '",$r2)[1])[0];
	$wr = explode("</div>",explode('<i class="fas fa-exclamation-circle"></i> ',$r2)[1])[0];
	$bc = explode(' token',explode('<b id="second">0</b> to get ',$r2)[1])[0];
	if(dash($ua)["bal"] > $bal1){
		if($ss){
			echo h."Success    ~> ".k.sprintf('%.8f',floatval($ss))." token".n;
		}else{
			echo h."Success    ~> ".k.sprintf('%.8f',floatval($bc))." token".n;
		}
		echo h."Balance    ~> ".k.dash($ua)["bal"].n;
	}
	if($wr){
		echo m."Error      ~> ".k.$wr.n;
	}
	print line();
}
wd:
$r1 = Run('https://'.host.'/dashboard',$ua);
$csrf=explode('"',explode('_token_name" value="',$r1)[1])[0];

$rad=explode('<div class="card-radio">',$r1);
for($ri=1;$ri<count($rad);$ri++){
	$cur=explode('</span>',explode('<span>',$rad[$ri])[1])[0];
	echo m.$ri." > ".k.$cur.n;
}
$me=readline(h.'Input Number '.m.'> ');
print line();
$metod=explode("']",explode("currencies['",$rad[$me])[1])[0];
$amm=explode('"',explode('id="tokenBalance" value="',$r1)[1])[0];

$data = http_build_query(["csrf_token_name"=>$csrf,"method"=>$metod,"amount"=>$amm,"wallet"=>$em]);
$r2= Run('https://'.host.'/dashboard/withdraw',$ua,$data);

$ss = explode("'",explode("Swal.fire('Good job!', '",$r2)[1])[0];
$wr=trim(strip_tags(explode("'",explode("Swal.fire('Error!', '",$r2)[1])[0]));
if($ss){
	echo h.$ss.n;
}else{
	echo m.$wr.n;
}
print line();
goto menu;
