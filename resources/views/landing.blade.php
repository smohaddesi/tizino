<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $c['hero_title'] }} | {{ $c['hero_subtitle'] }}</title>
<style>
:root{--brand:{{ $c['color_brand'] }};--bg:{{ $c['color_bg'] }};--card:{{ $c['color_card'] }};--tx:#e8edf7;--mut:#8d9ab5;--line:rgba(255,255,255,.08)}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Yekan','Vazirmatn',Tahoma,sans-serif;background:var(--bg);color:var(--tx);line-height:1.9;
 background-image:radial-gradient(circle at 80% 0,color-mix(in srgb,var(--brand) 14%,transparent),transparent 45%)}
a{color:inherit;text-decoration:none}
.ic{width:24px;height:24px}
.wrap{max-width:1140px;margin:auto;padding:0 20px}
nav{display:flex;align-items:center;justify-content:space-between;padding:20px 0;gap:16px;flex-wrap:wrap}
.logo{font-weight:800;font-size:1.5rem;color:var(--brand)}
.links{display:flex;gap:24px;color:var(--mut)}
.links a:hover{color:var(--tx)}
.btn{display:inline-block;padding:10px 26px;border-radius:999px;border:1px solid var(--brand);color:var(--brand);font-weight:700}
.btn.fill{background:var(--brand);color:#fff;box-shadow:0 0 24px color-mix(in srgb,var(--brand) 55%,transparent)}
.hero{display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:center;padding:50px 0}
.hero h1{font-size:3.2rem;line-height:1.3}
.hero h2{font-size:1.9rem;color:var(--brand);margin-bottom:14px}
.hero p{color:var(--mut);margin-bottom:28px;max-width:480px}
.tcard{background:var(--card);border:1px solid var(--line);border-radius:22px;padding:26px;box-shadow:0 20px 60px rgba(0,0,0,.35)}
.tcard h3{margin-bottom:18px}
.trow{display:flex;align-items:center;gap:22px;flex-wrap:wrap}
.ring{--p:{{ $c['card_percent'] }};width:130px;height:130px;border-radius:50%;display:grid;place-items:center;
 background:conic-gradient(var(--brand) calc(var(--p)*1%),rgba(255,255,255,.08) 0)}
.ring span{width:100px;height:100px;border-radius:50%;background:var(--card);display:grid;place-items:center;font-size:1.6rem;font-weight:800}
.mini{display:grid;gap:8px;flex:1;min-width:150px;color:var(--mut)}
.mini div{display:flex;align-items:center;gap:8px}
.mini b{color:var(--tx);margin-inline-start:auto}
.tcard .btn{width:100%;text-align:center;margin-top:20px}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin:20px 0 50px}
.stat{background:var(--card);border:1px solid var(--line);border-radius:18px;padding:20px;display:flex;align-items:center;gap:16px}
.stat .ic,.feat .ic{color:var(--brand);width:34px;height:34px}
.stat b{font-size:1.6rem;display:block;line-height:1.3}
.stat small{color:var(--mut)}
.sec-t{text-align:center;font-size:1.7rem;margin-bottom:26px}
.feats{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:60px}
.feat{background:var(--card);border:1px solid var(--line);border-radius:18px;padding:24px;text-align:center}
.feat h4{margin:10px 0 4px}.feat p{color:var(--mut);font-size:.9rem}
details{background:var(--card);border:1px solid var(--line);border-radius:14px;padding:14px 20px;margin-bottom:10px}
summary{cursor:pointer;font-weight:700}
details p{color:var(--mut);margin-top:8px}
.faq{max-width:760px;margin:0 auto 60px}
.cta{text-align:center;background:var(--card);border:1px solid var(--line);border-radius:24px;padding:44px 20px;margin-bottom:50px}
.cta p{color:var(--mut);margin:8px 0 22px}
footer{text-align:center;color:var(--mut);padding:26px 0;border-top:1px solid var(--line);font-size:.9rem}
@media(max-width:800px){.hero{grid-template-columns:1fr}.hero h1{font-size:2.4rem}.links{display:none}}
</style>
@include('partials.site-font')
</head>
<body>
<div class="wrap">
<nav>
 <a class="logo" href="/">{{ $c['hero_title'] }}</a>
 <div class="links">@foreach($c['nav_links'] ?? [] as $l)<a href="{{ $l['url'] }}">{{ $l['label'] }}</a>@endforeach</div>
 <div><a class="btn" href="/login">{{ $c['btn_login'] }}</a> <a class="btn fill" href="/register">{{ $c['btn_register'] }}</a></div>
</nav>

<section class="hero">
 <div>
  <h1>{{ $c['hero_title'] }}</h1>
  <h2>{{ $c['hero_subtitle'] }}</h2>
  <p>{{ $c['hero_text'] }}</p>
  <a class="btn fill" href="/register">{{ $c['hero_btn_text'] }}</a>
 </div>
 @if($c['card_enabled'])
 <div class="tcard">
  <h3>{{ $c['card_title'] }}</h3>
  <div class="trow">
   <div class="ring"><span>{{ $c['card_percent_fa'] }}٪</span></div>
   <div class="mini">
    <div>{{ $c['card_progress_label'] }}</div>
    <div>@include('landing.icon',['name'=>'list']) تعداد سوالات <b>{{ $c['card_total'] }}</b></div>
    <div>@include('landing.icon',['name'=>'check']) پاسخ صحیح <b>{{ $c['card_correct'] }}</b></div>
    <div>@include('landing.icon',['name'=>'cross']) پاسخ غلط <b>{{ $c['card_wrong'] }}</b></div>
    <div>@include('landing.icon',['name'=>'clock']) زمان <b>{{ $c['card_time'] }}</b></div>
   </div>
  </div>
  <a class="btn" href="/register">{{ $c['card_btn_text'] }}</a>
 </div>
 @endif
</section>

<section class="stats">
 @foreach($c['stats'] as $s)
 <div class="stat">@include('landing.icon',['name'=>$s['icon'] ?? 'star'])<div><b>{{ $s['value'] }}</b><small>{{ $s['label'] }}</small></div></div>
 @endforeach
</section>

<h2 class="sec-t">{{ $c['why_title'] }}</h2>
<section class="feats">
 @foreach($c['features'] ?? [] as $f)
 <div class="feat">@include('landing.icon',['name'=>$f['icon'] ?? 'star'])<h4>{{ $f['title'] }}</h4><p>{{ $f['text'] ?? '' }}</p></div>
 @endforeach
</section>

@if($c['faq_enabled'])
<section class="faq" id="faq">
 <h2 class="sec-t">{{ $c['faq_title'] }}</h2>
 @foreach($c['faqs'] ?? [] as $f)
 <details><summary>{{ $f['q'] }}</summary><p>{{ $f['a'] }}</p></details>
 @endforeach
</section>
@endif

@if($c['cta_enabled'])
<section class="cta">
 <h2>{{ $c['cta_title'] }}</h2>
 <p>{{ $c['cta_text'] }}</p>
 <a class="btn fill" href="/register">{{ $c['cta_btn_text'] }}</a>
</section>
@endif
</div>
<footer>{{ $c['footer_text'] }} © {{ $c['footer_copyright'] }}</footer>
</body>
</html>
