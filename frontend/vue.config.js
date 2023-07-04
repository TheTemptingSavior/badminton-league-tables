// vue.config.js
module.exports = {
    chainWebpack: config => {
        config.plugin('define')
            .tap( args => {
                args[0]['process.env']['VUE_APP_LEAGUE_NAME'] = JSON.stringify(process.env.VUE_APP_LEAGUE_NAME);
                return args;
            })
    }
}
