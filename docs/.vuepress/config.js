import {defaultTheme} from '@vuepress/theme-default'
import {defineUserConfig} from 'vuepress/cli'
import {viteBundler} from '@vuepress/bundler-vite'
import {searchPlugin} from "@vuepress/plugin-search";

export default defineUserConfig({
  lang: 'en-US',
  title: 'Laravel Purity',
  description: 'Filter and Sort Laravel Queries Elegantly',
  base: '/laravel-purity/',

  theme: defaultTheme({
    logo: '/images/purity-logo.png',
    navbar: ['/', 'docs'],
    repo: 'abbasudo/laravel-purity',
    docsRepo: 'abbasudo/laravel-purity',
    docsDir: 'docs',
  }),
  plugins: [searchPlugin({}),],
  head: [['link', { rel: 'icon', href: '/laravel-purity/images/favicon.ico' }]],
  bundler: viteBundler(),
})
