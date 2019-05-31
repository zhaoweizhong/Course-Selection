export default {
  namespaced: true,
  state: {
    isMobile: false,
    theme: 'dark',
    layout: 'side',
    systemName: 'Course Selection System',
    copyright: '2019 Some rights reserved.\n This software is licensed under the MIT License.',
    footerLinks: [
      {link: 'https://github.com/zhaoweizhong/Course-Selection', icon: 'github'}
    ]
  },
  mutations: {
    setDevice (state, isMobile) {
      state.isMobile = isMobile
    },
    setTheme (state, theme) {
      state.theme = theme
    },
    setLayout (state, layout) {
      state.layout = layout
    }
  }
}
