module.exports = {
  title: 'Sage Directives',
  description: 'Empower your Roots Sage workflow',
  base: '/sage-directives-docs/',

  head: [
    [
      "link",
      {
        href:
          "https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i",
        rel: "stylesheet",
        type: "text/css"
      }
    ]
  ],

  themeConfig: {
    sidebarDepth: 2,

    nav: [
      { text: 'Discourse', link: 'https://discourse.roots.io/' },
      { text: 'Changelog', link: 'https://github.com/log1x/sage-directives/blob/master/CHANGELOG.md' },
      { text: 'GitHub', link: 'https://github.com/log1x/sage-directives' }
    ],

    sidebar: [
      {
        title: 'Getting Started',
        collapsable: false,
        children: ['installation'],
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
