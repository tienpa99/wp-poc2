import { createApp, defineAsyncComponent } from 'vue/dist/vue.esm-bundler'
import { createStore } from "vuex";
import entities from "./../../../store/modules/entities";
import booking from "./../../../store/modules/booking";
import { provide, ref, reactive, readonly } from "vue";
import VueGtag from "vue-gtag";
import { install, init } from "./facebookPixel.js";

// It is necessary to investigate what is the best practice
// import axios from './plugins/axios'

const StepFormWrapper = defineAsyncComponent({
  loader: () => import('../../../views/public/StepForm/BookingStepForm.vue'),
  delay: 0
})

const CatalogFormWrapper = defineAsyncComponent({
  loader: () => import('../../../views/public/CatalogForm/CatalogForm.vue'),
  delay: 0
})

if (typeof window.ameliaShortcodeData === 'undefined') {
  window.ameliaShortcodeData = [{counter: null}]
}

const dynamicCdn = window.wpAmeliaUrls.wpAmeliaPluginURL + 'v3/public/';

window.__dynamic_handler__ = function(importer) {
  return dynamicCdn + 'assets/' + importer;
}
// @ts-ignore
window.__dynamic_preload__ = function(preloads) {
  return preloads.map(preload => dynamicCdn + preload);
}

if (window.ameliaShortcodeDataTriggered !== undefined) {
  window.ameliaShortcodeDataTriggered.forEach((shortCodeData) => {
    let ameliaPopUpLoaded = false

    let ameliaBookingButtonLoadInterval = setInterval(
      function () {
        let ameliaPopUpButtons = shortCodeData.trigger_type && shortCodeData.trigger_type === 'class' ? [...document.getElementsByClassName(shortCodeData.trigger)]
            : [document.getElementById(shortCodeData.trigger)]

        if (!ameliaPopUpLoaded && ameliaPopUpButtons.length > 0 && ameliaPopUpButtons[0] !== null && typeof ameliaPopUpButtons[0] !== 'undefined') {
          ameliaPopUpLoaded = true

          clearInterval(ameliaBookingButtonLoadInterval)
          ameliaPopUpButtons.forEach(ameliaPopUpButton => {
            ameliaPopUpButton.onclick = function () {
              let ameliaBookingFormLoadInterval = setInterval(
                  function () {
                    let ameliaPopUpForms = document.getElementsByClassName('amelia-skip-load-' + shortCodeData.counter)

                    if (ameliaPopUpForms.length) {
                      clearInterval(ameliaBookingFormLoadInterval)
                      for (let i = 0; i < ameliaPopUpForms.length; i++) {
                        if (!ameliaPopUpForms[i].classList.contains('amelia-v2-booking-' + shortCodeData.counter + '-loaded')) {
                          createAmelia(shortCodeData)
                        }
                      }
                    }
                  }, 1000
              )
            }
          })

        }
      }, 1000
    )
  })
}

window.ameliaShortcodeData.forEach((item) => {
  createAmelia(item)
})


function createAmelia(shortcodeData) {
  const settings = reactive(window.wpAmeliaSettings)

  let app = createApp({
    setup() {
      const baseURLs = reactive(window.wpAmeliaUrls)
      const labels = reactive(window.wpAmeliaLabels)
      const localLanguage = ref(window.localeLanguage[0])
      const licence = reactive({
        isBasic: false,
        isPro: false,
        isDeveloper: false,
        isLite: true
      })
      provide('settings', readonly(settings))
      provide('baseUrls', readonly(baseURLs))
      provide('labels', readonly(labels))
      provide('localLanguage', readonly(localLanguage))
      provide('shortcodeData', readonly(ref(shortcodeData)))
      provide('licence', licence)
    }
  })

  if (settings.googleTag.id) {
    app.use(VueGtag, {
      config: {id: window.wpAmeliaSettings.googleTag.id}
    })
  }

  if (settings.facebookPixel.id) {
    install()

    init(window.wpAmeliaSettings.facebookPixel.id)
  }

  app
    .component('StepFormWrapper', StepFormWrapper)
    .component('CatalogFormWrapper', CatalogFormWrapper)
    .use(
      createStore({
        modules: {
          entities,
          booking,
        },
      })
    )
    .mount(`#amelia-v2-booking${shortcodeData.counter !== null ? '-' + shortcodeData.counter : ''}`)
}

window.amelia = {load: createAmelia}
