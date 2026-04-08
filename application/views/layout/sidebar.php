<div class="bg-light border-end p-3" style="width:240px">

    <h6 class="text-center text-muted mb-3">MENU</h6>

    <?php $role_id = $this->session->userdata('role_id'); ?>


    <?php if ($role_id == 1): ?>

        <a href="<?= site_url('admin') ?>" class="menu-link"> Dashboard</a>

        <!-- System Users -->
        <a class="menu-link" data-bs-toggle="collapse" href="#userMenu">
            Parking ▾
        </a>
        <div class="collapse ms-3" id="userMenu">
            <a href="<?= site_url('parking') ?>" class="menu-link">• Parking List</a>
            <a href="<?= site_url('parking/add') ?>" class="menu-link">• Add parking</a>
        </div>

        <a class="menu-link" data-bs-toggle="collapse" href="#watchmanMenu">
            Watchman ▾
        </a>
        <div class="collapse ms-3" id="watchmanMenu">
            <a href="<?= site_url('watchman/list') ?>" class="menu-link">• Watchman List</a>
            <a href="<?= site_url('watchman/add') ?>" class="menu-link">• Add Watchman</a>
        </div>

         <a href="<?= site_url('parkbooking/my_bookings') ?>" class="menu-link"> My Bookings</a>

        
    <?php endif; ?>


</div>

<style>
    .menu-link {
        display: block;
        padding: 10px 12px;
        margin-bottom: 6px;
        border-radius: 8px;
        color: #333;
        text-decoration: none;
        font-weight: 500;
    }

    .menu-link:hover {
        background: #06606aff;
        color: white;
    }
</style>