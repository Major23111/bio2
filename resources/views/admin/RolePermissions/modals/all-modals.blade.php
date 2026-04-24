@include('admin.RolePermissions.modals.add-role')
@include('admin.RolePermissions.modals.add-user')
@include('admin.RolePermissions.modals.add-override')
@include('admin.RolePermissions.modals.add-delegation')
@include('admin.RolePermissions.modals.impersonation')

<style>
    [data-role-modal-root] .role-modal-scroll {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    [data-role-modal-root] .role-modal-scroll::-webkit-scrollbar {
        width: 6px;
    }

    [data-role-modal-root] .role-modal-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }
</style>
