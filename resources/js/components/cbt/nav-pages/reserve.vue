<div class="col-md-3">
                    <h4 class="card-title">Add New Department</h4>
                    <hr class="bg-white">
                    <form action="" method="post" id="newDepartmentForm">
                       <!-- {{csrfkey}} -->
                        <div v-if="errors.length" class="alert alert-danger" role="alert">
                            <strong v-for="(erorr, key) in errors" :key="key">
                                <p>{{`${key}: ${erorr}`}}</p>
                            </strong>
                        </div>
                        <div class="form-group">
                            <label for="department">Department</label>
                            <input type="text" v-model="deptData.department" name="department" id="department" class="form-control form-control-sm" placeholder="Department">
                        </div>

                        <div class="form-group">
                            <label for="department_code">Department Code</label>
                            <input type="text" v-model="deptData.department_code" name="department_code" id="department_code" class="form-control form-control-sm" placeholder="Department Code">
                        </div>
                        
                        <div class="form-group">
                           <button type="button" class="btn btn-danger btn-sm btn-block" 
                           @click.prevent="newDepartment">Create</button>
                        </div>
                    </form>
                </div>


// ----------------- Admin Dashboard Reserved ------------------------------

<template>
  <div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href>
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-school"></i>
        </div>
        <div class="sidebar-brand-text mx-2">
          MCCST
          <sup></sup>
        </div>
      </a>
      <!-- Divider -->
      <hr class="sidebar-divider my-0" />

      <li class="nav-item no-arrow">
        <a
          class="nav-link collapsed"
          href="#"
          data-toggle="collapse"
          data-target="#userSettings"
          aria-expanded="true"
          aria-controls="userSettings"
        >
          <b class="p-0 m-0">
            <i class="fas fa-chalkboard-teacher"></i>
            {{currentUser.username}}
          </b>
        </a>
        <div id="userSettings" class="collapse" aria-labelledby="headingUtilities">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Settings:</h6>
            <a class="collapse-item" href @click.prevent="getPageBody('settings')">
              <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
              Settings
            </a>
            <a class="collapse-item" :href="logout">
              <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
              Logout
            </a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider" />

      <div v-for="(group, key) in navGroups" :key="group.id">
        <div class="sidebar-heading">Manage {{group.name}}</div>

        <li class="nav-item">
          <a
            class="nav-link collapsed"
            href="#"
            data-toggle="collapse"
            :data-target="`#collapse${key}`"
            aria-expanded="true"
            aria-controls="collapseTwo"
          >
            <i class="fas fa-tasks"></i>
            <span>{{group.name}}</span>
          </a>
          <div
            :id="`collapse${key}`"
            class="collapse"
            aria-labelledby="headingTwo"
            data-parent="#accordionSidebar"
          >
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header">{{group.name}}:</h6>
              <div v-for="nav in navbars" :key="nav.id">
                <a
                  v-if="nav.navgroup_id === group.id"
                  class="collapse-item"
                  href
                  id="addRemoveDept"
                  @click.prevent="getPageBody(nav.name)"
                >{{nav.name}}</a>
              </div>
            </div>
          </div>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider" />
      </div>

      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <vue-progress-bar></vue-progress-bar>
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
          <i class="fa fa-bars"></i>
        </button>
        <!-- <div class="navbar navbar-expand navbar-primary static-top shadow">
                    
        </div>-->
        <!-- <navbar-top :currentUser="currentUser" :logout="logout" :pageName="pageBody"></navbar-top> -->
        <div class="container-fluid m-0 p-1">
          <component :is="pageBody" :currentUser="currentUser"></component>
        </div>
      </div>
      <footer class="sticky-footer shadow p-2">
        <div class="container">
          <div class="copyright text-center my-auto">
            <b class="float-left">Copyright &copy; MCCHST 2019</b>
            <b class="float-right">Design & Developed by KAMEEN IT Solution 2348034932065</b>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      navGroups: [],
      navbars: [],
      requestResponse: {},
      pageBody: "",
      currentUser: ""
    };
  },

  props: {
    logout: "",
    csrfkey: "",
    user: ""
  },
  created() {
    this.currentUser = JSON.parse(this.user);
    Toast.fire({
      type: "success",
      title: "Signed in successfully"
    });
    this.getNavGroups();
    this.getNavbars();
  },
  methods: {
    getNavGroups() {
      axios.get("admin/navbargroup").then(res => {
        this.navGroups = res.data;
      });
    },

    getNavbars() {
      axios.get("admin/navbars").then(res => {
        this.navbars = res.data;
      });
    },

    getPageBody(name) {
      name = name.replace(/\s/g, "-");
      this.pageBody = name.toLowerCase();
    }
  }
};
</script>

<style scoped>
</style>

// new Vue({
//   el: '#app',
//   data: {
//     date: null
//   }
// });
// <div id="app">
//   <date-picker date-format="yy-mm-dd" v-once></date-picker>
//   <p>{{ date }}</p>
// </div>
// The datepicker widget has an onSelect callback that is called when a date is picked. We can then use our component to emit this date via a custom event:

// mounted: function() {
//   var self = this;
//   $(this.$el).datepicker({
//     dateFormat: this.dateFormat,
//     onSelect: function(date) {
//       self.$emit('update-date', date);
//     }
//   });
// }
// Our root instance can listen to the custom event and receive the new date:

// <div id="app">
//   <date-picker @update-date="updateDate" date-format="yy-mm-dd" v-once></date-picker>
//   <p>{{ date }}</p>
// </div>
// new Vue({
//   el: '#app',
//   data: {
//     date: null
//   },
//   methods: {
//     updateDate: function(date) {
//       this.date = date;
//     }
//   }
// });

import Button from 'Button.vue'
import Vue from 'vue'
var ComponentClass = Vue.extend(Button)
var instance = new ComponentClass({
    propsData: { type: 'primary' }
})
instance.$slots.default = [ 'Click me!' ]
instance.$mount() // pass nothing
this.$refs.container.appendChild(instance.$el)
                