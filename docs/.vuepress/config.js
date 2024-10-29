import {defaultTheme} from '@vuepress/theme-default'
import {defineUserConfig} from 'vuepress/cli'
import {viteBundler} from '@vuepress/bundler-vite'
import {searchPlugin} from "@vuepress/plugin-search";
import {googleAnalyticsPlugin} from "@vuepress/plugin-google-analytics";
import {sitemapPlugin} from "@vuepress/plugin-sitemap";

export default defineUserConfig({
  lang: 'en-US',
  title: 'Laravel Purity',
  description: 'Filter and Sort Laravel Queries Elegantly',
  base: '/laravel-purity/',

  theme: defaultTheme({
    logo: '/images/purity-logo.png',
    navbar: [
      'introduction',
      {
        text: 'Guide',
        prefix: 'guide/',
        children: [
          'installation.md',
          'basic-usage.md',
          'tutorials.md'
        ],
      },
      {
        text: 'JS Examples',
        prefix: 'js-examples/',
        children: [
          'available-methods.md',
          'filter.md',
          'sort.md',
        ],
      },
      {
        text: 'Advanced',
        prefix: 'advanced/',
        children: [
          'rename.md',
          'relation.md',
          'param.md',
          'allowed.md',
          'livewire.md',
          'silent.md',
          {
            text: 'Filter',
            prefix: 'filter/',
            children: [
              'restrict.md',
              'custom.md',
            ],
          },
          {
            text: 'Sort',
            prefix: 'sort/',
            children: [
              'null-sort.md',
            ],
          },
          'upgrade.md',
        ],
      },
      {
        text: 'Client Package',
        link: 'https://github.com/hidevs/purity-client-js',
      },
      {
        text: 'Ask a Question',
        link: 'https://github.com/abbasudo/laravel-purity/discussions',
      },
    ],
    repo: 'abbasudo/laravel-purity',
    docsRepo: 'abbasudo/laravel-purity',
    docsDir: 'docs',
    docsBranch: 'master',
  }),
  plugins: [

    sitemapPlugin({
      hostname: 'https://abbasudo.github.io/laravel-purity',
    }),
    searchPlugin({}),
    googleAnalyticsPlugin({
      id: 'G-C75TGXT64W',
    }),
  ],
  head: [
    ['link', { rel: 'icon', href: '/laravel-purity/images/favicon.ico' }],
    ['meta', { name: 'google-site-verification', content: 'wKNJm2JHXoFOhYhTop4QN7qDRgx1rnXwJoZd80gCwes' }],
  ],
  bundler: viteBundler(),
})
