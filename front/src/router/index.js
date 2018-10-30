import Vue from 'vue'
import Router from 'vue-router'
import Projects from '@/components/Projects'
import ProjectList from '@/components/turmas/List'
import ProjectShow from '@/components/turmas/Show'
import Schedules from '@/components/Schedules'
import Users from '@/components/Users'
import StudentsManager from '@/components/StudentsManager'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      redirect: '/projects'
    },
    {
      path: '/projects',
      component: Projects,
      children: [
        {
          path: '',
          component: ProjectList
        },
        {
          path: ':id',
          component: ProjectShow
        }
      ]
    }, 
    {
      path: '/schedules',
      component: Schedules
    },
    {
      path: '/users',
      component: Users
    },
    {
      path: '/students-manager',
      component: StudentsManager
    }
  ]
})
