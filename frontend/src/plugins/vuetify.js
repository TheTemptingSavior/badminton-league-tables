import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

const opts = {
    theme: {
        themes: {
            light: {
                primary: '#42a5f5',
                secondary: '#b0bec5',
                accent: '#ff9800',
                success: '#66BB6A',
                error: '#EF5350',
            },
        },
    }
}

export default new Vuetify(opts)