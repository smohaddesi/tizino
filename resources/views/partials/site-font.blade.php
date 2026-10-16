@php($__fontCss = \App\Models\SiteFont::activeCss())
@if($__fontCss !== '')
<style id="site-font">{!! $__fontCss !!}</style>
@endif
