import{_ as t,c as s,a as e,b as r,o as n}from"./app-_5XRzQQS.js";const i={},o={align:"center"},l=["src"],p=e("h1",{align:"center"},"Elegant way to filter and sort queries in Laravel",-1),c=r(`<p><a href="https://github.com/abbasudo/laravel-purity/actions/workflows/tests.yml" target="_blank" rel="noopener noreferrer"><img src="https://github.com/abbasudo/laravel-purity/actions/workflows/tests.yml/badge.svg" alt="Tests"></a><a href="https://github.com/abbasudo/laravel-purity" target="_blank" rel="noopener noreferrer"><img src="http://poser.pugx.org/abbasudo/laravel-purity/license" alt="License"></a><a href="https://packagist.org/packages/abbasudo/laravel-purity" target="_blank" rel="noopener noreferrer"><img src="http://poser.pugx.org/abbasudo/laravel-purity/v" alt="Latest Unstable Version"></a><a href="https://packagist.org/packages/abbasudo/laravel-purity" target="_blank" rel="noopener noreferrer"><img src="http://poser.pugx.org/abbasudo/laravel-purity/require/php" alt="PHP Version Require"></a><a href="https://packagist.org/packages/abbasudo/laravel-purity" target="_blank" rel="noopener noreferrer"><img src="https://github.styleci.io/repos/603931433/shield" alt="StyleCI"></a></p><p>Laravel Purity is an elegant and efficient filtering and sorting package for Laravel, designed to simplify complex data filtering and sorting logic for eloquent queries. By simply adding <code>filter()</code> to your Eloquent query, you can add the ability for frontend users to apply filters based on URL query string parameters like a breeze.</p><p>Features :</p><ul><li>Livewire support</li><li>Rename and restrict fields</li><li>Various filter methods</li><li>Simple installation and usage</li><li>Filter by relation columns</li><li>Custom filters</li><li>Multi-column sort</li></ul><p>Laravel Purity is not only developer-friendly but also front-end developer-friendly. Frontend developers can effortlessly use filtering and sorting of the APIs by using the popular <a href="https://www.npmjs.com/package/qs" target="_blank" rel="noopener noreferrer">JavaScript qs</a> package.</p><p>The way this package handles filters is inspired by strapi&#39;s <a href="https://docs.strapi.io/dev-docs/api/rest/filters-locale-publication#filtering" target="_blank" rel="noopener noreferrer">filter</a> and <a href="https://docs.strapi.io/dev-docs/api/rest/sort-pagination#sorting" target="_blank" rel="noopener noreferrer">sort</a> functionality.</p><h2 id="how-does-purity-work" tabindex="-1"><a class="header-anchor" href="#how-does-purity-work"><span>How Does Purity Work?</span></a></h2><p>Here is a basic usage example to clarify Purity&#39;s use case.</p><p>Add <code>filter()</code> to your query.</p><div class="language-php line-numbers-mode" data-highlighter="prismjs" data-ext="php" data-title="php"><pre class="language-php"><code><span class="line"><span class="token variable">$posts</span> <span class="token operator">=</span> <span class="token class-name static-context">Post</span><span class="token operator">::</span><span class="token function">filter</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token operator">-&gt;</span><span class="token function">get</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><p>That&#39;s it! Now you can filter your posts by adding query string parameters to the URL.</p><div class="language-text line-numbers-mode" data-highlighter="prismjs" data-ext="text" data-title="text"><pre class="language-text"><code><span class="line">GET /api/posts?filters[title][$contains]=Purity</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div>`,12);function u(a,d){return n(),s("div",null,[e("p",o,[e("img",{src:a.$withBase("/images/purity-logo.png"),alt:"Social Card of Laravel Purity"},null,8,l),p]),c])}const h=t(i,[["render",u],["__file","introduction.html.vue"]]),f=JSON.parse('{"path":"/introduction.html","title":"Introduction","lang":"en-US","frontmatter":{"title":"Introduction"},"headers":[{"level":2,"title":"How Does Purity Work?","slug":"how-does-purity-work","link":"#how-does-purity-work","children":[]}],"git":{"updatedTime":1718407870000,"contributors":[{"name":"Abbas mkhzomi","email":"amkhzomi@gmail.com","commits":2}]},"filePathRelative":"introduction.md"}');export{h as comp,f as data};
