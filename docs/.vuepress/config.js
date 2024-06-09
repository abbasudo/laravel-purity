import {defaultTheme} from '@vuepress/theme-default'
import {defineUserConfig} from 'vuepress/cli'
import {viteBundler} from '@vuepress/bundler-vite'

export default defineUserConfig({
  lang: 'en-US',

  title: 'Laravel Purity', description: 'Filter and Sort Laravel Queries Elegantly',

  theme: defaultTheme({
    logo: '/images/purity-logo.png',

    navbar: ['/', '/get-started'],
    repo: 'abbasudo/laravel-purity',
    docsRepo: 'abbasudo/laravel-purity',
    docsDir: 'docs',
  }),

  head: [['link', { rel: 'icon', href: '/images/favicon.ico' }]], bundler: viteBundler(),
})
