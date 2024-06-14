import{_ as p,r as i,c as o,a,d as s,e,w as t,b as l,o as c}from"./app-DTVgM2yK.js";const r={},d=l(`<p>Purity auto-detects relations in the model and allows you to filter and sort by them.</p><h4 id="filter-by-relation" tabindex="-1"><a class="header-anchor" href="#filter-by-relation"><span>Filter by Relation</span></a></h4><p>firstly, define the relation in the model</p><div class="language-php line-numbers-mode" data-highlighter="prismjs" data-ext="php" data-title="php"><pre class="language-php"><code><span class="line"><span class="token keyword">use</span> <span class="token package">Illuminate<span class="token punctuation">\\</span>Database<span class="token punctuation">\\</span>Eloquent<span class="token punctuation">\\</span>Relations<span class="token punctuation">\\</span>HasMany</span><span class="token punctuation">;</span></span>
<span class="line"></span>
<span class="line"><span class="token keyword">class</span> <span class="token class-name-definition class-name">Post</span> <span class="token keyword">extends</span> <span class="token class-name">Model</span></span>
<span class="line"><span class="token punctuation">{</span></span>
<span class="line">    <span class="token keyword">use</span> <span class="token package">Filterable</span><span class="token punctuation">;</span></span>
<span class="line">    </span>
<span class="line">    <span class="token keyword">public</span> <span class="token keyword">function</span> <span class="token function-definition function">tags</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">:</span> <span class="token class-name return-type">HasMany</span> <span class="token comment">// This is mandatory</span></span>
<span class="line">    <span class="token punctuation">{</span></span>
<span class="line">        <span class="token keyword">return</span> <span class="token variable">$this</span><span class="token operator">-&gt;</span><span class="token function">hasMany</span><span class="token punctuation">(</span><span class="token class-name static-context">Tag</span><span class="token operator">::</span><span class="token keyword">class</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">    <span class="token punctuation">}</span></span>
<span class="line"><span class="token punctuation">}</span></span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div></div></div><p>Edit <code>$filterFields</code> property in the related model. By default, Purity allows all fields to be filtered.</p><div class="language-php line-numbers-mode" data-highlighter="prismjs" data-ext="php" data-title="php"><pre class="language-php"><code><span class="line"><span class="token keyword">class</span> <span class="token class-name-definition class-name">Tags</span> <span class="token keyword">extends</span> <span class="token class-name">Model</span></span>
<span class="line"><span class="token punctuation">{</span></span>
<span class="line">    <span class="token keyword">use</span> <span class="token package">Filterable</span><span class="token punctuation">;</span></span>
<span class="line">    </span>
<span class="line">    <span class="token keyword">protected</span> <span class="token variable">$filterFields</span> <span class="token operator">=</span> <span class="token punctuation">[</span></span>
<span class="line">        <span class="token string single-quoted-string">&#39;title&#39;</span><span class="token punctuation">,</span></span>
<span class="line">    <span class="token punctuation">]</span><span class="token punctuation">;</span></span>
<span class="line"><span class="token punctuation">}</span></span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div></div></div>`,6),u=l(`<h4 id="sort-by-relation" tabindex="-1"><a class="header-anchor" href="#sort-by-relation"><span>Sort by Relation</span></a></h4><p>Currently, the following relationship types are supported.</p><ul><li>One to One</li><li>One to Many</li><li>Many to Many</li></ul><p>Return type of the relations mandatory as below in order to sort by relationships.</p><div class="language-php line-numbers-mode" data-highlighter="prismjs" data-ext="php" data-title="php"><pre class="language-php"><code><span class="line"><span class="token keyword">use</span> <span class="token package">Illuminate<span class="token punctuation">\\</span>Database<span class="token punctuation">\\</span>Eloquent<span class="token punctuation">\\</span>Relations<span class="token punctuation">\\</span>HasMany</span><span class="token punctuation">;</span></span>
<span class="line"></span>
<span class="line"><span class="token keyword">class</span> <span class="token class-name-definition class-name">Post</span> <span class="token keyword">extends</span> <span class="token class-name">Model</span></span>
<span class="line"><span class="token punctuation">{</span></span>
<span class="line">    <span class="token keyword">use</span> <span class="token package">Sortable</span><span class="token punctuation">;</span></span>
<span class="line">    </span>
<span class="line">    <span class="token keyword">public</span> <span class="token keyword">function</span> <span class="token function-definition function">tags</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">:</span> <span class="token class-name return-type">HasMany</span> <span class="token comment">// This is mandatory</span></span>
<span class="line">    <span class="token punctuation">{</span></span>
<span class="line">        <span class="token keyword">return</span> <span class="token variable">$this</span><span class="token operator">-&gt;</span><span class="token function">hasMany</span><span class="token punctuation">(</span><span class="token class-name static-context">Tag</span><span class="token operator">::</span><span class="token keyword">class</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">    <span class="token punctuation">}</span></span>
<span class="line"><span class="token punctuation">}</span></span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div></div></div>`,5);function k(m,v){const n=i("RouteLink");return c(),o("div",null,[d,a("p",null,[s("Apply relation filtering examples at "),e(n,{to:"/js-examples/filter.html#relation-filtering"},{default:t(()=>[s("examples page")]),_:1})]),u,a("p",null,[s("Apply relation filtering examples at "),e(n,{to:"/js-examples/sort.html#apply-sort-by-relationships"},{default:t(()=>[s("examples page")]),_:1})])])}const h=p(r,[["render",k],["__file","relation.html.vue"]]),y=JSON.parse('{"path":"/advanced/relation.html","title":"Rename Fields","lang":"en-US","frontmatter":{"title":"Rename Fields","prev":{"text":"Rename Fields","link":"/advanced/rename"},"next":{"text":"Allowed Fields","link":"/advanced/allowed"}},"headers":[],"git":{"updatedTime":1718395613000,"contributors":[{"name":"Abbas mkhzomi","email":"amkhzomi@gmail.com","commits":1}]},"filePathRelative":"advanced/relation.md"}');export{h as comp,y as data};
