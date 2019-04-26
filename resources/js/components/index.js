import Vue from 'vue'
import Card from './Card'
import Child from './Child'
import Button from './Button'

// Components that are registered globaly.
[
  Card,
  Child,
  Button,
].forEach(Component => {
  Vue.component(Component.name, Component)
})
