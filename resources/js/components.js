/*
     SUPPORT FUNCTIONS
 */

import VuePassword from "vue-password";
// Password strength library
Vue.component("VuePassword", () => import("vue-password"));


/*
    Passport
 */
Vue.component('passport-clients', () => import(/* webpackChunkName:"passport-clients" */ './components/passport/Clients.vue'));
Vue.component('passport-authorized-clients', () => import(/* webpackChunkName:"passport-authorized-clients" */ './components/passport/AuthorizedClients.vue'));
Vue.component('passport-personal-access-tokens', () => import(/* webpackChunkName:"passport-personal-access-tokens" */ './components/passport/PersonalAccessTokens.vue'));

// Vue.component( 'passport-clients', require('./components/passport/Clients.vue'));
// Vue.component( 'passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));
// Vue.component( 'passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue'));

/*
    Password
 */
Vue.component("password-reset-form", () =>
    import(/* webpackChunkName:"password-reset-form" */ "./components/PasswordResetForm.vue")
);

Vue.component('change-password-form', () =>
    import(/* webpackChunkName:"change-password-form" */ './components/change_password/ChangePasswordForm.vue')
);

/*
    User Invite
 */
Vue.component("invite-grid", () =>
    import(/* webpackChunkName:"invite-grid" */ "./components/invite/InviteGrid.vue")
);

Vue.component("create-password-form", () =>
    import(/* webpackChunkName:"create-password-form" */ "./components/invite/CreatePasswordForm.vue")
);

/*
     Roles
 */

Vue.component("role-description-grid", () =>
    import(
        /* webpackChunkName:"role-description-grid" */ "./components/RoleDescription/RoleDescriptionGrid.vue"
        )
);
Vue.component("role-description-form", () =>
    import(
        /* webpackChunkName:"role-description-form" */ "./components/RoleDescription/RoleDescriptionForm.vue"
        )
);
Vue.component("role-description-show", () =>
    import(
        /* webpackChunkName:"role-description-show" */ "./components/RoleDescription/RoleDescriptionShow.vue"
        )
);

Vue.component("role-grid", () =>
    import(/* webpackChunkName:"role-grid" */ "./components/roles/RoleGrid.vue")
);
Vue.component("role-form", () =>
    import(/* webpackChunkName:"role-form" */ "./components/roles/RoleForm.vue")
);
Vue.component("role-show", () =>
    import(/* webpackChunkName:"role-show" */ "./components/roles/RoleShow.vue")
);

/*
    SS
 */

Vue.component("ss-date-picker", () =>
    import(/* webpackChunkName:"ss-date-picker" */ "./components/SS/DatePicker.vue")
);

Vue.component("ss-grid-column-header", () =>
    import(
        /* webpackChunkName:"ss-grid-column-header" */ "./components/SS/SsGridColumnHeader.vue"
        )
);
Vue.component("ss-grid-pagination", () =>
    import(
        /* webpackChunkName:"ss-grid-pagination" */ "./components/SS/SsGridPagination.vue"
        )
);
Vue.component("ss-grid-pagination-location", () =>
    import(
        /* webpackChunkName:"ss-grid-pagination-location" */ "./components/SS/SsPaginationLocation.vue"
        )
);

Vue.component("std-form-group", () =>
    import(
        /* webpackChunkName:"std-form-group" */ "./components/SS/StdFormGroup.vue"
        )
);
Vue.component("fld-input", () =>
    import(/* webpackChunkName:"fld-input" */ "./components/SS/FldInput.vue")
);




Vue.component("fld-text-area", () =>
    import(
        /* webpackChunkName:"fld-text-area" */ "./components/SS/FldTextArea.vue"
        )
);
Vue.component("fld-text-editor", () =>
    import(
        /* webpackChunkName:"fld-text-editor" */ "./components/SS/FldTextEditor.vue"
        )
);
Vue.component("fld-checkbox", () =>
    import(
        /* webpackChunkName:"fld-checkbox" */ "./components/SS/FldCheckBox.vue"
        )
);
Vue.component("fld-state", () =>
    import(/* webpackChunkName:"fld-state" */ "./components/SS/FldState.vue")
);

Vue.component("dsp-boolean", () =>
    import(
        /* webpackChunkName:"dsp-boolean" */ "./components/SS/DspBoolean.vue"
        )
);
Vue.component("dsp-textarea", () =>
    import(
        /* webpackChunkName:"dsp-textarea" */ "./components/SS/DspTextArea.vue"
        )
);
Vue.component("dsp-text", () =>
    import(/* webpackChunkName:"dsp-text" */ "./components/SS/DspText.vue")
);
Vue.component("dsp-date", () =>
    import(/* webpackChunkName:"dsp-date" */ "./components/SS/DspDate.vue")
);
Vue.component("dsp-dollar", () =>
    import(/* webpackChunkName:"dsp-dollar" */ "./components/SS/DspDollar.vue")
);

Vue.component("dsp-decimal", () =>
    import(/* webpackChunkName:"dsp-decimal" */ "./components/SS/DspDecimal.vue")
);


Vue.component("modal-window", () =>
    import(/* webpackChunkName:"modal-window" */ "./components/SS/ModalWindow.vue")
);



/*
     UI
 */
Vue.component("ui-field-view", require("./components/SS/UiFieldView.vue").default);
Vue.component("ui-select-pick-one", require("./components/SS/UiSelectPickOne.vue").default);

Vue.component("ui-pick-roles", () =>
    import(
        /* webpackChunkName:"ui-pick-roles" */ "./components/SS/UiPickRoles.vue"
        )
);

Vue.component("search-form-group", () =>
    import(
        /* webpackChunkName:"search-form-group" */ "./components/SS/SearchFormGroup.vue"
        )
);



// Vue.component('organization-grid', () => import(/* webpackChunkName:"organization-grid" */ './components/organizations/OrganizationGrid.vue'));
// Vue.component('organization-form', () => import(/* webpackChunkName:"organization-form" */ './components/organizations/OrganizationForm.vue'));
// Vue.component('organization-show', () => import(/* webpackChunkName:"organization-Show" */ './components/organizations/OrganizationShow.vue'));