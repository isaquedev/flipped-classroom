import Vue from 'vue'
import Router from 'vue-router'
import SchoolClasses from '@/components/SchoolClasses'
import SchoolClassesList from '@/components/schoolclasses/List'
import SchoolClassesShow from '@/components/schoolclasses/Show'
import Users from '@/components/Users'
import Questionnaires from '@/components/Questionnaires'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      redirect: '/schoolclasses'
    },
    {
      path: '/schoolclasses',
      component: SchoolClasses,
      children: [
        {
          path: '',
          component: SchoolClassesList
        },
        {
          path: ':id',
          component: SchoolClassesShow
        }
      ]
    }, 
    {
      path: '/users',
      component: Users
    },
    {
      path: '/questionnaires',
      component: Questionnaires
    }
  ]
})
