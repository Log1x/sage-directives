module.exports = {
  title: 'Sage Directives',
  description: 'Empower your Roots Sage workflow',
  base: '/sage-directives-docs/',

  plugins: ['@vuepress/back-to-top'],

  head: [
    [
      "link",
      {
        href: "https://fonts.googleapis.com/css?family=Nunito:300,400,400i,500,600,800",
        rel: "stylesheet",
        type: "text/css"
      }
    ]
  ],

  themeConfig: {
    sidebarDepth: 2,

    nav: [
      { text: 'Support', link: 'https://discourse.roots.io/t/blade-directives-for-sage/14301' },
      { text: 'Changelog', link: 'https://github.com/log1x/sage-directives/blob/master/CHANGELOG.md' },
      { text: 'GitHub', link: 'https://github.com/log1x/sage-directives' }
    ],

    sidebar: [
      {
        title: 'Getting Started',
        collapsable: false,
        children: ['/'],
      },
      {
        title: 'Usage',
        collapsable: false,
        children: prefix('usage', [
          'wordpress',
          'acf',
          'helpers'
        ])
      }
    ]
  }
}

function prefix(prefix, children) {
  return children.map(child => `${prefix}/${child}`);
}
