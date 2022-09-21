<style>
    .grecaptcha-badge { visibility: hidden; }
    .modal-content
    {
        background-color:transparent;
        border:none!important;
    }

    .modal-dialog
    {
        position: relative;
        top: -8%;
    }
	/*#popup_data*/
	/*{*/
		/*display: flex!important;*/

		/*justify-content: center;*/

		/*overflow: hidden!important;*/
		/*align-items: center;*/
	/*}*/
    button.close
    {
        position: relative;
        top: -5px;
		font-size:2rem;
		opacity:1;

    }
    img.img-responsive
    {
        border-radius:10px;
    }
    .cursorPointer
    {
        cursor:pointer;
    }
    .linkRow
    {
        margin: 0px!important;
        position: relative;
        top: -64px;
    }
    @media only screen and (max-width:780px)
    {
        .cursorPointer
        {
            height:60px!important;
        }

        button.close
        {
            display:none;
        }


    }
    @media only screen and (min-width:780px)
    {
        .cursorPointer
        {
            height: 140px!important;
            position: relative!important;
            top: -80px!important;
        }

    }










</style>
<div class="header-wrapper">
    <?php
    $contact_us_info = $this->db->get('contact_us')->row_array();
    $popup_data = $this->db->select('*')->from('popup')->get()->row_array();
    ?>
    <div class="back-effect">
        <a href="javascript:void(0)" onclick="closeNav()"><i class="close1">×</i></a>
    </div>
    <div class="mobile-menu sidenav" id="mySidenav">
        <div class="account-links">

            <div class="user">
                <?php
                $user = $this->session->userdata('logged_in');
                // print_r($user);die('zain');
                $login_class = '';
                if(!empty($this->session->userdata('logged_in'))) :
                    $login_class = 'hide-on-login'; ?>
                    <div class="lang-dropdown dropdown mr-0">
                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="image flex">
                                        <?php
                                        if(isset($user->picture) && $user->picture != ''){
                                            $file = $this->db->get_where('files',['id' => $user->picture])->row_array();

                                            ?>
                                            <img src="<?= base_url($file['path'] . $file['name']); ?>" alt="">
                                            <!-- A -->
                                        <?php }else{ ?>
                                            <img src="<?= ASSETS_IMAGES;?>no_image.png">
                                            <!-- A -->
                                        <?php }?>
                                        <!-- <img src="assets/images/US.png" alt=""> -->
                                    </span>
                            <span class="name text-truncate">
                                        <!-- Chris -->
                                <?= (isset($user->fname) && !empty($user->fname)) ? $user->fname : '';?>
                                    </span>
                        </button>
                        <div class="dropdown-menu long-menu" aria-labelledby="dropdownMenuButton">
                            <ul>
                                <li class="<?= (isset($profile_active)) ? $profile_active : '';?>">
                                    <a  href="<?= base_url('customer/profile');?>">
                                        <svg width="18" height="19" viewBox="0 0 18 19" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.1469 12.6178C13.6848 13.1557 14.1084 13.7832 14.4037 14.4811C14.7096 15.2053 14.8643 15.9717 14.8678 16.7645C14.8678 17.1143 14.5848 17.3973 14.235 17.3973C13.8852 17.3973 13.6021 17.1143 13.6021 16.7645C13.6021 15.5357 13.124 14.3809 12.2557 13.5125C11.3873 12.6441 10.2324 12.166 9.00371 12.166C7.775 12.166 6.62012 12.6441 5.75176 13.5125C4.8834 14.3809 4.40527 15.5357 4.40527 16.7645C4.40527 17.1143 4.12227 17.3973 3.77246 17.3973C3.42266 17.3973 3.13965 17.1143 3.13965 16.7645C3.13965 15.9717 3.29434 15.2053 3.6002 14.4811C3.89551 13.7832 4.31914 13.1557 4.85703 12.6178C5.39492 12.0799 6.02246 11.6562 6.72031 11.3609C6.95762 11.259 7.20195 11.1764 7.44805 11.1078C6.75195 10.8723 6.11387 10.4785 5.57949 9.94414C4.66367 9.03008 4.16094 7.81367 4.16094 6.52168C4.16094 5.22969 4.66543 4.01328 5.57949 3.09922C6.49355 2.1834 7.70996 1.68066 9.00195 1.68066C10.2939 1.68066 11.5104 2.18516 12.4244 3.09922C13.3402 4.01328 13.843 5.22969 13.843 6.52168C13.843 7.81367 13.3385 9.03008 12.4244 9.94414C11.89 10.4785 11.252 10.8723 10.5559 11.1078C10.802 11.1764 11.0463 11.2607 11.2836 11.3609C11.9814 11.6562 12.609 12.0799 13.1469 12.6178ZM9.00019 2.94653C7.02968 2.94653 5.4248 4.54966 5.4248 6.52192C5.4248 8.49243 7.02792 10.0973 9.00019 10.0973C10.9725 10.0973 12.5756 8.49419 12.5756 6.52192C12.5756 4.54966 10.9707 2.94653 9.00019 2.94653Z"/>
                                        </svg>

                                        <?= $this->lang->line('my_profile');?>
                                    </a>
                                </li>
                                <li class="<?= (isset($deposit_active)) ? $deposit_active : '';?>">
                                    <a href="<?= base_url('customer/deposit');?>">
                                        <svg width="16" height="13" viewBox="0 0 16 13" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.8735 0.0476074H1.125C0.503125 0.0476074 0 0.550727 0 1.17261V3.05542V4.55542V10.8773C0 11.4992 0.503125 12.0023 1.125 12.0023H14.875C15.4969 12.0023 16 11.4992 16 10.8773V4.55542V3.05542V1.17261C15.9985 0.550727 15.4937 0.0476074 14.8735 0.0476074ZM14.8735 10.8773H1.125V4.55542H14.8735V10.8773ZM1.125 1.17261V3.05542H14.8735V1.17261H1.125ZM3.70313 9.02417C4.08281 9.02417 4.39063 8.71637 4.39063 8.3367C4.39063 7.95697 4.08281 7.64917 3.70313 7.64917C3.32344 7.64917 3.01563 7.95697 3.01563 8.3367C3.01563 8.71637 3.32344 9.02417 3.70313 9.02417ZM6.9922 8.3367C6.9922 8.71637 6.6844 9.02417 6.30469 9.02417C5.925 9.02417 5.61719 8.71637 5.61719 8.3367C5.61719 7.95697 5.925 7.64917 6.30469 7.64917C6.6844 7.64917 6.9922 7.95697 6.9922 8.3367Z"/>
                                        </svg>
                                        <?= $this->lang->line('payments');?>
                                    </a>
                                </li>
                                <!-- <li class="<?//= (isset($security_active)) ? $security_active : '';?>">
                                            <a href="<?//= base_url('customer/item_deposit');?>">
                                                <svg width="16" height="13" viewBox="0 0 16 13" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.8735 0.0476074H1.125C0.503125 0.0476074 0 0.550727 0 1.17261V3.05542V4.55542V10.8773C0 11.4992 0.503125 12.0023 1.125 12.0023H14.875C15.4969 12.0023 16 11.4992 16 10.8773V4.55542V3.05542V1.17261C15.9985 0.550727 15.4937 0.0476074 14.8735 0.0476074ZM14.8735 10.8773H1.125V4.55542H14.8735V10.8773ZM1.125 1.17261V3.05542H14.8735V1.17261H1.125ZM3.70313 9.02417C4.08281 9.02417 4.39063 8.71637 4.39063 8.3367C4.39063 7.95697 4.08281 7.64917 3.70313 7.64917C3.32344 7.64917 3.01563 7.95697 3.01563 8.3367C3.01563 8.71637 3.32344 9.02417 3.70313 9.02417ZM6.9922 8.3367C6.9922 8.71637 6.6844 9.02417 6.30469 9.02417C5.925 9.02417 5.61719 8.71637 5.61719 8.3367C5.61719 7.95697 5.925 7.64917 6.30469 7.64917C6.6844 7.64917 6.9922 7.95697 6.9922 8.3367Z"/>
                                                </svg>
                                                <?//= $this->lang->line('item_security');?>
                                            </a>
                                        </li> -->
                                <li class="<?= (isset($account_active)) ? $account_active : '';?>">
                                    <a href="<?= base_url('customer');?>">
                                        <svg width="18" height="19" viewBox="0 0 18 19" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9 0.0241699C4.02891 0.0241699 0 4.05308 0 9.02417C0 13.9953 4.02891 18.0242 9 18.0242C13.9711 18.0242 18 13.9953 18 9.02417C18 4.05308 13.9711 0.0241699 9 0.0241699ZM14.4686 14.4927C13.7584 15.2029 12.9305 15.7619 12.0094 16.1503C11.0566 16.5546 10.0441 16.7585 9 16.7585C7.95586 16.7585 6.94336 16.5546 5.99063 16.1521C5.06953 15.7619 4.24336 15.2046 3.53145 14.4945C2.82129 13.7843 2.2623 12.9564 1.87383 12.0353C1.46953 11.0808 1.26562 10.0683 1.26562 9.02417C1.26562 7.98003 1.46953 6.96753 1.87207 6.0148C2.2623 5.0937 2.81953 4.26753 3.52969 3.55562C4.23984 2.84546 5.06777 2.28647 5.98887 1.898C6.94336 1.4937 7.95586 1.28979 9 1.28979C10.0441 1.28979 11.0566 1.4937 12.0094 1.89624C12.9305 2.28647 13.7566 2.8437 14.4686 3.55386C15.1787 4.26401 15.7377 5.09194 16.1262 6.01304C16.5305 6.96753 16.7344 7.98003 16.7344 9.02417C16.7344 10.0683 16.5305 11.0808 16.1279 12.0335C15.7377 12.9546 15.1805 13.7826 14.4686 14.4927ZM9.63281 8.88354H12.8848C13.2346 8.88354 13.5176 9.16655 13.5176 9.51636C13.5176 9.86616 13.2346 10.1492 12.8848 10.1492H9.63281C8.9332 10.1492 8.36719 9.58315 8.36719 8.88354V4.66479C8.36719 4.31499 8.6502 4.03198 9 4.03198C9.3498 4.03198 9.63281 4.31499 9.63281 4.66479V8.88354Z" />
                                        </svg>
                                        <?= $this->lang->line('history');?>
                                    </a>
                                </li>
                                <li class="<?= (isset($favorite_active)) ? $favorite_active : '';?>">
                                    <a href="<?= base_url('customer/favorite') ?>">
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.4089 1.64185C16.9024 2.13883 17.2905 2.72361 17.5627 3.37863C17.8437 4.05649 17.9859 4.77824 17.9842 5.52458C17.9789 7.156 17.2747 8.67502 16.0542 9.6918C15.3521 10.3204 14.2071 11.3669 13.0446 12.4292C12.9017 12.5599 12.7585 12.6908 12.6158 12.8212L12.4812 12.9442C11.2041 14.1115 10.0155 15.1979 9.4302 15.7187C9.31079 15.8259 9.1545 15.8856 8.99294 15.8856C8.83313 15.8856 8.67859 15.8259 8.55567 15.7187C7.71976 14.9759 5.67567 13.1074 4.03372 11.606L3.91113 11.4939C3.01069 10.671 2.30675 10.0276 2.05635 9.80244C2.00542 9.75502 1.9545 9.71112 1.90006 9.66546C0.705911 8.65395 0.0140085 7.15249 0.00171581 5.54215C-0.00530858 4.79932 0.135179 4.07756 0.416155 3.39619C0.686594 2.73941 1.07645 2.15288 1.57167 1.65239C2.5305 0.683024 3.78435 0.14917 5.10318 0.14917C6.60815 0.14917 8.0183 0.834048 8.99118 2.03522C9.96406 0.834048 11.3742 0.14917 12.8792 0.14917C14.1998 0.14917 15.4536 0.679511 16.4089 1.64185ZM12.8792 1.49609C11.7482 1.49609 10.6893 2.0317 9.97108 2.96595C9.73927 3.268 9.37225 3.44887 8.99293 3.44712C8.61186 3.44712 8.24659 3.268 8.01303 2.96595C7.29479 2.03346 6.2341 1.49961 5.10493 1.49961C4.14961 1.49961 3.23293 1.89122 2.52698 2.60419C2.1582 2.97473 1.86669 3.41551 1.65947 3.91424C1.44874 4.42702 1.34337 4.97141 1.34688 5.53336C1.35566 6.74507 1.87371 7.876 2.76932 8.63639C2.81713 8.67595 2.86184 8.71552 2.90491 8.75363L2.91332 8.76107C2.92737 8.77512 2.94142 8.78741 2.95547 8.7997C3.18903 9.00868 3.81596 9.58117 4.60971 10.3082L5.44035 11.0668C6.69771 12.2153 8.12015 13.5131 8.99293 14.3016C9.56542 13.7835 10.4277 12.9968 11.6921 11.8395C13.3094 10.3574 14.4772 9.29492 15.1603 8.68204L15.192 8.65395C15.6275 8.28868 15.9892 7.81805 16.2403 7.29297C16.5038 6.73629 16.6372 6.13746 16.639 5.51404C16.639 4.95034 16.5319 4.40419 16.3211 3.89317C16.1157 3.39795 15.8242 2.96068 15.4571 2.5919C14.7564 1.88595 13.8415 1.49609 12.8792 1.49609Z"/>
                                        </svg>
                                        <?= $this->lang->line('my_wishlist');?>
                                    </a>
                                </li>
                                <!-- <li class="<?//= (isset($inventory_active)) ? $inventory_active : '';?>">
                                            <a href="<?//= base_url('customer/inventory') ?>">
                                                <svg width="17" height="17" viewBox="0 0 17 17" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14.8735 1.01733H3.10791C2.4876 1.01733 1.98291 1.52202 1.98291 2.14233V13.8923C1.98291 14.5127 2.4876 15.0173 3.10791 15.0173H7.43604C7.74698 15.0173 7.99851 14.7658 7.99851 14.4548C7.99851 14.1439 7.74698 13.8923 7.43604 13.8923H3.10791V2.14233H14.8735V13.8923H13.561C13.2501 13.8923 12.9985 14.1439 12.9985 14.4548C12.9985 14.7658 13.2501 15.0173 13.561 15.0173H14.8735C15.4938 15.0173 15.9985 14.5127 15.9985 13.8923V2.14233C15.9985 1.52202 15.4938 1.01733 14.8735 1.01733Z"/>
                                                    <path d="M10.9985 14.4549C10.9985 14.6558 11.1058 14.8415 11.2798 14.942C11.4539 15.0425 11.6683 15.0425 11.8423 14.942C12.0163 14.8415 12.1235 14.6558 12.1235 14.4549C12.1235 14.2539 12.0163 14.0682 11.8423 13.9677C11.6683 13.8672 11.4539 13.8672 11.2798 13.9677C11.1058 14.0682 10.9985 14.2539 10.9985 14.4549Z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.56104 4.01733H9.43604C9.74697 4.01733 9.99851 4.26889 9.99851 4.57983C9.99851 4.89077 9.74697 5.14233 9.43604 5.14233H4.56104C4.2501 5.14233 3.99854 4.89077 3.99854 4.57983C3.99854 4.26889 4.2501 4.01733 4.56104 4.01733ZM10.9985 6.57983C10.9985 6.26889 11.2501 6.01733 11.561 6.01733C11.872 6.01733 12.1235 6.26889 12.1235 6.57983V12.4548C12.1235 12.7658 11.872 13.0173 11.561 13.0173C11.2501 13.0173 10.9985 12.7658 10.9985 12.4548V6.57983ZM7.43604 7.01733H4.56104C4.2501 7.01733 3.99854 7.26887 3.99854 7.57987C3.99854 7.8908 4.2501 8.14233 4.56104 8.14233H7.43604C7.74698 8.14233 7.99851 7.8908 7.99851 7.57987C7.99851 7.26887 7.74698 7.01733 7.43604 7.01733ZM4.56104 10.0173H7.43604C7.74698 10.0173 7.99851 10.2689 7.99851 10.5799C7.99851 10.8908 7.74698 11.1423 7.43604 11.1423H4.56104C4.2501 11.1423 3.99854 10.8908 3.99854 10.5799C3.99854 10.2689 4.2501 10.0173 4.56104 10.0173Z">
                                                </svg>
                                                <?//= $this->lang->line('inventory');?>
                                            </a>
                                        </li> -->
                                <!-- <li class="<?//= (isset($payments_active)) ? $payments_active : '';?>">
                                            <a href="<?//= base_url('user-payment') ?>">
                                                <svg width="17" height="17" viewBox="0 0 17 17" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14.8735 1.01733H3.10791C2.4876 1.01733 1.98291 1.52202 1.98291 2.14233V13.8923C1.98291 14.5127 2.4876 15.0173 3.10791 15.0173H7.43604C7.74698 15.0173 7.99851 14.7658 7.99851 14.4548C7.99851 14.1439 7.74698 13.8923 7.43604 13.8923H3.10791V2.14233H14.8735V13.8923H13.561C13.2501 13.8923 12.9985 14.1439 12.9985 14.4548C12.9985 14.7658 13.2501 15.0173 13.561 15.0173H14.8735C15.4938 15.0173 15.9985 14.5127 15.9985 13.8923V2.14233C15.9985 1.52202 15.4938 1.01733 14.8735 1.01733Z"/>
                                                    <path d="M10.9985 14.4549C10.9985 14.6558 11.1058 14.8415 11.2798 14.942C11.4539 15.0425 11.6683 15.0425 11.8423 14.942C12.0163 14.8415 12.1235 14.6558 12.1235 14.4549C12.1235 14.2539 12.0163 14.0682 11.8423 13.9677C11.6683 13.8672 11.4539 13.8672 11.2798 13.9677C11.1058 14.0682 10.9985 14.2539 10.9985 14.4549Z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.56104 4.01733H9.43604C9.74697 4.01733 9.99851 4.26889 9.99851 4.57983C9.99851 4.89077 9.74697 5.14233 9.43604 5.14233H4.56104C4.2501 5.14233 3.99854 4.89077 3.99854 4.57983C3.99854 4.26889 4.2501 4.01733 4.56104 4.01733ZM10.9985 6.57983C10.9985 6.26889 11.2501 6.01733 11.561 6.01733C11.872 6.01733 12.1235 6.26889 12.1235 6.57983V12.4548C12.1235 12.7658 11.872 13.0173 11.561 13.0173C11.2501 13.0173 10.9985 12.7658 10.9985 12.4548V6.57983ZM7.43604 7.01733H4.56104C4.2501 7.01733 3.99854 7.26887 3.99854 7.57987C3.99854 7.8908 4.2501 8.14233 4.56104 8.14233H7.43604C7.74698 8.14233 7.99851 7.8908 7.99851 7.57987C7.99851 7.26887 7.74698 7.01733 7.43604 7.01733ZM4.56104 10.0173H7.43604C7.74698 10.0173 7.99851 10.2689 7.99851 10.5799C7.99851 10.8908 7.74698 11.1423 7.43604 11.1423H4.56104C4.2501 11.1423 3.99854 10.8908 3.99854 10.5799C3.99854 10.2689 4.2501 10.0173 4.56104 10.0173Z">
                                                </svg>
                                                <?//= $this->lang->line('my_payable_new');?>
                                            </a>
                                        </li> -->
                                <!-- <li class="<?//= (isset($document_active)) ? $document_active : '';?>">
                                            <a href="<?//= base_url('customer/uploaded_docs') ?>">
                                                <svg width="17" height="17" viewBox="0 0 17 17" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14.8735 1.01733H3.10791C2.4876 1.01733 1.98291 1.52202 1.98291 2.14233V13.8923C1.98291 14.5127 2.4876 15.0173 3.10791 15.0173H7.43604C7.74698 15.0173 7.99851 14.7658 7.99851 14.4548C7.99851 14.1439 7.74698 13.8923 7.43604 13.8923H3.10791V2.14233H14.8735V13.8923H13.561C13.2501 13.8923 12.9985 14.1439 12.9985 14.4548C12.9985 14.7658 13.2501 15.0173 13.561 15.0173H14.8735C15.4938 15.0173 15.9985 14.5127 15.9985 13.8923V2.14233C15.9985 1.52202 15.4938 1.01733 14.8735 1.01733Z"/>
                                                    <path d="M10.9985 14.4549C10.9985 14.6558 11.1058 14.8415 11.2798 14.942C11.4539 15.0425 11.6683 15.0425 11.8423 14.942C12.0163 14.8415 12.1235 14.6558 12.1235 14.4549C12.1235 14.2539 12.0163 14.0682 11.8423 13.9677C11.6683 13.8672 11.4539 13.8672 11.2798 13.9677C11.1058 14.0682 10.9985 14.2539 10.9985 14.4549Z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.56104 4.01733H9.43604C9.74697 4.01733 9.99851 4.26889 9.99851 4.57983C9.99851 4.89077 9.74697 5.14233 9.43604 5.14233H4.56104C4.2501 5.14233 3.99854 4.89077 3.99854 4.57983C3.99854 4.26889 4.2501 4.01733 4.56104 4.01733ZM10.9985 6.57983C10.9985 6.26889 11.2501 6.01733 11.561 6.01733C11.872 6.01733 12.1235 6.26889 12.1235 6.57983V12.4548C12.1235 12.7658 11.872 13.0173 11.561 13.0173C11.2501 13.0173 10.9985 12.7658 10.9985 12.4548V6.57983ZM7.43604 7.01733H4.56104C4.2501 7.01733 3.99854 7.26887 3.99854 7.57987C3.99854 7.8908 4.2501 8.14233 4.56104 8.14233H7.43604C7.74698 8.14233 7.99851 7.8908 7.99851 7.57987C7.99851 7.26887 7.74698 7.01733 7.43604 7.01733ZM4.56104 10.0173H7.43604C7.74698 10.0173 7.99851 10.2689 7.99851 10.5799C7.99851 10.8908 7.74698 11.1423 7.43604 11.1423H4.56104C4.2501 11.1423 3.99854 10.8908 3.99854 10.5799C3.99854 10.2689 4.2501 10.0173 4.56104 10.0173Z">
                                                </svg>
                                                <?//= $this->lang->line('my_docs');?>
                                            </a>
                                        </li> -->
                                <li>
                                    <a href="<?php echo base_url('terms-conditions');?>">
                                        <svg width="17" height="17" viewBox="0 0 17 17" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.8735 1.01733H3.10791C2.4876 1.01733 1.98291 1.52202 1.98291 2.14233V13.8923C1.98291 14.5127 2.4876 15.0173 3.10791 15.0173H7.43604C7.74698 15.0173 7.99851 14.7658 7.99851 14.4548C7.99851 14.1439 7.74698 13.8923 7.43604 13.8923H3.10791V2.14233H14.8735V13.8923H13.561C13.2501 13.8923 12.9985 14.1439 12.9985 14.4548C12.9985 14.7658 13.2501 15.0173 13.561 15.0173H14.8735C15.4938 15.0173 15.9985 14.5127 15.9985 13.8923V2.14233C15.9985 1.52202 15.4938 1.01733 14.8735 1.01733Z"/>
                                            <path d="M10.9985 14.4549C10.9985 14.6558 11.1058 14.8415 11.2798 14.942C11.4539 15.0425 11.6683 15.0425 11.8423 14.942C12.0163 14.8415 12.1235 14.6558 12.1235 14.4549C12.1235 14.2539 12.0163 14.0682 11.8423 13.9677C11.6683 13.8672 11.4539 13.8672 11.2798 13.9677C11.1058 14.0682 10.9985 14.2539 10.9985 14.4549Z" />
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.56104 4.01733H9.43604C9.74697 4.01733 9.99851 4.26889 9.99851 4.57983C9.99851 4.89077 9.74697 5.14233 9.43604 5.14233H4.56104C4.2501 5.14233 3.99854 4.89077 3.99854 4.57983C3.99854 4.26889 4.2501 4.01733 4.56104 4.01733ZM10.9985 6.57983C10.9985 6.26889 11.2501 6.01733 11.561 6.01733C11.872 6.01733 12.1235 6.26889 12.1235 6.57983V12.4548C12.1235 12.7658 11.872 13.0173 11.561 13.0173C11.2501 13.0173 10.9985 12.7658 10.9985 12.4548V6.57983ZM7.43604 7.01733H4.56104C4.2501 7.01733 3.99854 7.26887 3.99854 7.57987C3.99854 7.8908 4.2501 8.14233 4.56104 8.14233H7.43604C7.74698 8.14233 7.99851 7.8908 7.99851 7.57987C7.99851 7.26887 7.74698 7.01733 7.43604 7.01733ZM4.56104 10.0173H7.43604C7.74698 10.0173 7.99851 10.2689 7.99851 10.5799C7.99851 10.8908 7.74698 11.1423 7.43604 11.1423H4.56104C4.2501 11.1423 3.99854 10.8908 3.99854 10.5799C3.99854 10.2689 4.2501 10.0173 4.56104 10.0173Z">
                                        </svg>
                                        <?= $this->lang->line('terms');?>
                                    </a>
                                </li>
                                <li class="<?= (isset($sell_my_item)) ? $sell_my_item : '';?>">
                                    <a href="<?php echo base_url('customer/inventory');?>">
                                        <svg width="17" height="17" viewBox="0 0 17 17" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.8735 1.01733H3.10791C2.4876 1.01733 1.98291 1.52202 1.98291 2.14233V13.8923C1.98291 14.5127 2.4876 15.0173 3.10791 15.0173H7.43604C7.74698 15.0173 7.99851 14.7658 7.99851 14.4548C7.99851 14.1439 7.74698 13.8923 7.43604 13.8923H3.10791V2.14233H14.8735V13.8923H13.561C13.2501 13.8923 12.9985 14.1439 12.9985 14.4548C12.9985 14.7658 13.2501 15.0173 13.561 15.0173H14.8735C15.4938 15.0173 15.9985 14.5127 15.9985 13.8923V2.14233C15.9985 1.52202 15.4938 1.01733 14.8735 1.01733Z"/>
                                            <path d="M10.9985 14.4549C10.9985 14.6558 11.1058 14.8415 11.2798 14.942C11.4539 15.0425 11.6683 15.0425 11.8423 14.942C12.0163 14.8415 12.1235 14.6558 12.1235 14.4549C12.1235 14.2539 12.0163 14.0682 11.8423 13.9677C11.6683 13.8672 11.4539 13.8672 11.2798 13.9677C11.1058 14.0682 10.9985 14.2539 10.9985 14.4549Z" />
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.56104 4.01733H9.43604C9.74697 4.01733 9.99851 4.26889 9.99851 4.57983C9.99851 4.89077 9.74697 5.14233 9.43604 5.14233H4.56104C4.2501 5.14233 3.99854 4.89077 3.99854 4.57983C3.99854 4.26889 4.2501 4.01733 4.56104 4.01733ZM10.9985 6.57983C10.9985 6.26889 11.2501 6.01733 11.561 6.01733C11.872 6.01733 12.1235 6.26889 12.1235 6.57983V12.4548C12.1235 12.7658 11.872 13.0173 11.561 13.0173C11.2501 13.0173 10.9985 12.7658 10.9985 12.4548V6.57983ZM7.43604 7.01733H4.56104C4.2501 7.01733 3.99854 7.26887 3.99854 7.57987C3.99854 7.8908 4.2501 8.14233 4.56104 8.14233H7.43604C7.74698 8.14233 7.99851 7.8908 7.99851 7.57987C7.99851 7.26887 7.74698 7.01733 7.43604 7.01733ZM4.56104 10.0173H7.43604C7.74698 10.0173 7.99851 10.2689 7.99851 10.5799C7.99851 10.8908 7.74698 11.1423 7.43604 11.1423H4.56104C4.2501 11.1423 3.99854 10.8908 3.99854 10.5799C3.99854 10.2689 4.2501 10.0173 4.56104 10.0173Z">
                                        </svg>
                                        <?= $this->lang->line('sell_my_item');?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('logout');?>">
                                        <svg width="18" height="19" viewBox="0 0 18 19" fill="#FF5E5E" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 6.77417C9.3498 6.77417 9.63281 6.49116 9.63281 6.14136V0.656982C9.63281 0.307178 9.3498 0.0241699 9 0.0241699C8.6502 0.0241699 8.36719 0.307178 8.36719 0.656982V6.14136C8.36719 6.49116 8.6502 6.77417 9 6.77417Z"/>
                                            <path d="M9 18.0243C7.78535 18.0243 6.60586 17.787 5.49668 17.3176C4.42441 16.8641 3.46289 16.2137 2.63672 15.3876C1.81055 14.5614 1.16016 13.5999 0.706641 12.5276C0.237305 11.4184 0 10.2389 0 9.02427C0 7.59517 0.325195 6.22759 0.966797 4.96021C1.57852 3.75259 2.47324 2.68033 3.5543 1.85767C3.83203 1.64673 4.2293 1.69947 4.44023 1.9772C4.65117 2.25493 4.59844 2.6522 4.3207 2.86314C3.39258 3.56978 2.62266 4.49263 2.09707 5.52974C1.54512 6.62134 1.26562 7.79556 1.26562 9.02427C1.26562 10.0684 1.46953 11.0809 1.87383 12.0354C2.26406 12.9565 2.82129 13.7844 3.53145 14.4946C4.2416 15.2047 5.06953 15.7637 5.99063 16.1522C6.94336 16.5547 7.95586 16.7586 9 16.7586C10.0441 16.7586 11.0566 16.5547 12.0111 16.1504C12.9322 15.7602 13.7602 15.203 14.4703 14.4928C15.1805 13.7827 15.7395 12.9547 16.1279 12.0336C16.5305 11.0809 16.7344 10.0684 16.7344 9.02427C16.7344 7.79556 16.4549 6.62134 15.9029 5.53325C15.3773 4.49439 14.6074 3.57329 13.6793 2.86665C13.4016 2.65572 13.3471 2.25845 13.5598 1.98072C13.7707 1.70298 14.168 1.64849 14.4457 1.86118C15.525 2.68384 16.4197 3.75611 17.0332 4.96372C17.6748 6.23111 18 7.59693 18 9.02779C18 10.2424 17.7627 11.4219 17.2934 12.5311C16.8398 13.5999 16.1895 14.5614 15.3633 15.3876C14.5371 16.2137 13.5738 16.8624 12.5033 17.3159C11.3941 17.787 10.2146 18.0243 9 18.0243Z"/>
                                        </svg>
                                        <?= $this->lang->line('logout');?>
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                <?php else : ?>
                    <p class="m-0">
                        <a href="javascript:void(0)" id="loginClick" data-toggle="modal" data-target="#loginModal"><?= $this->lang->line('login_new');?></a>
                        <?= $this->lang->line('or');?>
                        <a href="javascript:void(0)" id="registerClick" data-toggle="modal" data-target="#registerModal"><?= $this->lang->line('register_new');?></a>
                    </p>
                <?php endif; ?>
            </div>


        </div>
        <ul class="side-items">

            <li>
                <a href="<?php echo base_url('customer/inventory?rurl=customer/inventory');?>">
                    <?= $this->lang->line('sell_my_item');?>
                </a>
            </li>
            <!-- <li>
                <a href="javascript:void(0)" data-toggle="modal" data-target="#registerModal">
					<?//= $this->lang->line('register_now'); ?>
                </a>
			</li> -->
            <li>
                <a href="<?php echo base_url('home/auction_guide');?>">
                    <?= $this->lang->line('auction_guides'); ?>
                </a>
            </li>
            <li>
                <a href="<?= (!empty($headerCategoryId)) ? base_url('search/').$headerCategoryId : 'javascript:void(0)'; ?>">
                    <?= $this->lang->line('auctions'); ?>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url('visitor/about_us');?>">
                    <?= $this->lang->line('about_new'); ?>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url('visitor/gallery');?>">
                    <?= $this->lang->line('new_media'); ?>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url('visitor/contact_us');?>" class="bord-botm">
                    <?= $this->lang->line('contact_us_new'); ?>
                </a>
            </li>
            <li>
                <a href="tel:<?= $contact_us_info['toll_free']; ?>">
                    <span class="material-icons">
                        local_phone
                    </span>
                    <p dir="ltr"><?php echo $contact_us_info['toll_free']; ?></p>
                </a>
            </li>
            <li>
                <a href="<?php echo 'mailto:'.$contact_us_info['email'];?>" class="bord-botm">
                    <span class="material-icons">
                        email
                    </span>
                    <p class="lowercase"><?php echo $contact_us_info['email']; ?></p>
                </a>
            </li>
        </ul>
        <div class="account-links">
            <div class="lang-dropdown dropdown mobile-lang">
                <?php
                $arabic_link = '<a href="'.base_url('language/arabic/?url=').$_SERVER['REQUEST_URI'].'" class="dropdown-item">AR</a>';
                $english_link = '<a href="'.base_url('language/english/?url=').$_SERVER['REQUEST_URI'].'" class="dropdown-item">EN</a>';
                $uaeImage = NEW_ASSETS_USER.'/new/images/UAE.jpg';
                $usImage = NEW_ASSETS_USER.'/new/images/US.png';

                if($this->session->userdata('site_lang')){
                    $language = $this->session->userdata('site_lang');
                    if($language == 'arabic'){
                        $changeButton = $english_link;
                        $currentLang = 'AR';
                        $image = $uaeImage;
                    }else{
                        $changeButton = $arabic_link;
                        $currentLang = 'EN';
                        $image = $usImage;
                    }
                }else{
                    $changeButton = $arabic_link;
                    $currentLang = 'EN';
                    $image = $usImage;
                }
                ?>
                <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="<?= $image; ?>" alt=""> <?= $currentLang; ?>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <!-- <a class="dropdown-item" href="#">AR</a> -->
                    <?= $changeButton; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="top-head">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <ul class="h-list">
                        <li>
                            <a href="#">
                                <span class="material-icons">
                                    local_phone
                                </span>
                                <?= $this->lang->line('toll_free');?>: <span dir="ltr" style="margin-left: 3px;"><?php echo $contact_us_info['toll_free']; ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo 'mailto:'.$contact_us_info['email'];?>">
                                <span class="material-icons">
                                    email
                                </span>
                                <?= $this->lang->line('email');?>: <?php echo $contact_us_info['email']; ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('visitor/contact_us');?>"><?= $this->lang->line('contact_us_new'); ?></a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-5">
                    <ul class="h-list justify-content-end">
                        <li>
                            <a href="<?php echo base_url('customer/inventory?rurl=customer/inventory');?>">
                                <?= $this->lang->line('sell_my_item');?>
                            </a>
                        </li>
                        <!-- <li style="display: none;">
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#registerModal">
								<?//= $this->lang->line('register_now'); ?>
                            </a>
                        </li> -->
                        <li>
                            <a href="<?php echo base_url('home/auction_guide');?>">
                                <?= $this->lang->line('auction_guides'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= (!empty($headerCategoryId)) ? base_url('search/').$headerCategoryId : 'javascript:void(0)'; ?>">
                                <?= $this->lang->line('auctions'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('visitor/about_us');?>">
                                <?= $this->lang->line('about_new'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <header>
        <?php
        $active_auction_categories = $this->db->select('*')->from('item_category')->where(['status' => 'active','show_web' => 'yes'])->order_by('sort_order', 'ASC')->get()->result_array();
        ?>
        <div class="container">
            <div class="inner">
                <div class="logo-holder">
                    <a href="#" class="menu-toggler" onclick="openNav()">
                        <i class="fa fa-bars"></i>
                    </a>
                    <?php if ($language == 'english') {
                        $logo = 'logo_header_english.svg';
                    }else{
                        $logo = 'logo_header_arabic.svg';
                    } ?>
                    <a href="<?= base_url(); ?>">
                        <img src="<?= NEW_ASSETS_USER; ?>/new/images/logo/<?= $logo;?>" alt="">
                    </a>
                </div>
                <form method="GET" action="<?= base_url('searchItems'); ?>" class="search-box">
                    <select name="categoryId" class="selectpicker">
                        <?php foreach ($active_auction_categories as $key => $category) {
                            $cat_title = json_decode($category['title']); ?>
                            <option <?= ($category['id'] == $headerCategoryId) ? 'selected="selected"' : ''; ?> value="<?= $category['id']; ?>"><?= $cat_title->$language; ?></option>
                        <?php } ?>
                    </select>
                    <input type="text"
                           name="query"
                           value="<?= (isset($_GET['query']) && !empty($_GET['query'])) ? $_GET['query'] : ''; ?>"
                           oninput="this.value=this.value.replace(/[^a-zA-Z0-9 ]/g,'');"
                           pattern="[^'\x22]+"
                           title="no quotes!"
                           class="form-control"
                           placeholder="<?= $this->lang->line('search_here');?>...">
                    <button type="submit" class="search-btn">
                        <span class="material-icons">
                            search
                        </span>
                    </button>
                </form>
                <div class="account-links">
                    <div class="lang-dropdown dropdown hide-on-1000">
                        <?php
                        $arabic_link = '<a href="'.base_url('language/arabic/?url=').$_SERVER['REQUEST_URI'].'" class="dropdown-item">AR</a>';
                        $english_link = '<a href="'.base_url('language/english/?url=').$_SERVER['REQUEST_URI'].'" class="dropdown-item">EN</a>';
                        $uaeImage = NEW_ASSETS_USER.'/new/images/UAE.jpg';
                        $usImage = NEW_ASSETS_USER.'/new/images/US.png';

                        if($this->session->userdata('site_lang')){
                            $language = $this->session->userdata('site_lang');
                            if($language == 'arabic'){
                                $changeButton = $english_link;
                                $currentLang = 'AR';
                                $image = $uaeImage;
                            }else{
                                $changeButton = $arabic_link;
                                $currentLang = 'EN';
                                $image = $usImage;
                            }
                        }else{
                            $changeButton = $arabic_link;
                            $currentLang = 'EN';
                            $image = $usImage;
                        }
                        ?>
                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?= $image; ?>" alt=""> <?= $currentLang; ?>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <!-- <a class="dropdown-item" href="#">AR</a> -->
                            <?= $changeButton; ?>
                        </div>
                    </div>
                    <div class="user">
                        <?php
                        $user = $this->session->userdata('logged_in');
                        // print_r($user);die('zain');
                        $login_class = '';
                        if(!empty($this->session->userdata('logged_in'))) :
                            $login_class = 'hide-on-login'; ?>
                            <div class="lang-dropdown dropdown mr-0">
                                <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="image flex">
                                        <?php
                                        if(isset($user->picture) && $user->picture != ''){
                                            $file = $this->db->get_where('files',['id' => $user->picture])->row_array();

                                            ?>
                                            <img src="<?= base_url($file['path'] . $file['name']); ?>" alt="">
                                            <!-- A -->
                                        <?php }else{ ?>
                                            <img src="<?= ASSETS_IMAGES;?>no_image.png">
                                            <!-- A -->
                                        <?php }?>
                                        <!-- <img src="assets/images/US.png" alt=""> -->
                                    </span>
                                    <span class="name text-truncate">
                                        <!-- Chris -->
                                        <?= (isset($user->fname) && !empty($user->fname)) ? $user->fname : '';?>
                                    </span>
                                </button>
                                <div class="dropdown-menu long-menu" aria-labelledby="dropdownMenuButton">
                                    <ul>
                                        <li class="<?= (isset($profile_active)) ? $profile_active : '';?>">
                                            <a  href="<?= base_url('customer/profile');?>">
                                                <svg width="18" height="19" viewBox="0 0 18 19" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.1469 12.6178C13.6848 13.1557 14.1084 13.7832 14.4037 14.4811C14.7096 15.2053 14.8643 15.9717 14.8678 16.7645C14.8678 17.1143 14.5848 17.3973 14.235 17.3973C13.8852 17.3973 13.6021 17.1143 13.6021 16.7645C13.6021 15.5357 13.124 14.3809 12.2557 13.5125C11.3873 12.6441 10.2324 12.166 9.00371 12.166C7.775 12.166 6.62012 12.6441 5.75176 13.5125C4.8834 14.3809 4.40527 15.5357 4.40527 16.7645C4.40527 17.1143 4.12227 17.3973 3.77246 17.3973C3.42266 17.3973 3.13965 17.1143 3.13965 16.7645C3.13965 15.9717 3.29434 15.2053 3.6002 14.4811C3.89551 13.7832 4.31914 13.1557 4.85703 12.6178C5.39492 12.0799 6.02246 11.6562 6.72031 11.3609C6.95762 11.259 7.20195 11.1764 7.44805 11.1078C6.75195 10.8723 6.11387 10.4785 5.57949 9.94414C4.66367 9.03008 4.16094 7.81367 4.16094 6.52168C4.16094 5.22969 4.66543 4.01328 5.57949 3.09922C6.49355 2.1834 7.70996 1.68066 9.00195 1.68066C10.2939 1.68066 11.5104 2.18516 12.4244 3.09922C13.3402 4.01328 13.843 5.22969 13.843 6.52168C13.843 7.81367 13.3385 9.03008 12.4244 9.94414C11.89 10.4785 11.252 10.8723 10.5559 11.1078C10.802 11.1764 11.0463 11.2607 11.2836 11.3609C11.9814 11.6562 12.609 12.0799 13.1469 12.6178ZM9.00019 2.94653C7.02968 2.94653 5.4248 4.54966 5.4248 6.52192C5.4248 8.49243 7.02792 10.0973 9.00019 10.0973C10.9725 10.0973 12.5756 8.49419 12.5756 6.52192C12.5756 4.54966 10.9707 2.94653 9.00019 2.94653Z"/>
                                                </svg>

                                                <?= $this->lang->line('my_profile');?>
                                            </a>
                                        </li>
                                        <li class="<?= (isset($deposit_active)) ? $deposit_active : '';?>">
                                            <a href="<?= base_url('customer/deposit');?>">
                                                <svg width="16" height="13" viewBox="0 0 16 13" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.8735 0.0476074H1.125C0.503125 0.0476074 0 0.550727 0 1.17261V3.05542V4.55542V10.8773C0 11.4992 0.503125 12.0023 1.125 12.0023H14.875C15.4969 12.0023 16 11.4992 16 10.8773V4.55542V3.05542V1.17261C15.9985 0.550727 15.4937 0.0476074 14.8735 0.0476074ZM14.8735 10.8773H1.125V4.55542H14.8735V10.8773ZM1.125 1.17261V3.05542H14.8735V1.17261H1.125ZM3.70313 9.02417C4.08281 9.02417 4.39063 8.71637 4.39063 8.3367C4.39063 7.95697 4.08281 7.64917 3.70313 7.64917C3.32344 7.64917 3.01563 7.95697 3.01563 8.3367C3.01563 8.71637 3.32344 9.02417 3.70313 9.02417ZM6.9922 8.3367C6.9922 8.71637 6.6844 9.02417 6.30469 9.02417C5.925 9.02417 5.61719 8.71637 5.61719 8.3367C5.61719 7.95697 5.925 7.64917 6.30469 7.64917C6.6844 7.64917 6.9922 7.95697 6.9922 8.3367Z"/>
                                                </svg>
                                                <?= $this->lang->line('payments');?>
                                            </a>
                                        </li>
                                        <!-- <li class="<?= (isset($security_active)) ? $security_active : '';?>">
                                            <a href="<?= base_url('customer/item_deposit');?>">
                                                <svg width="16" height="13" viewBox="0 0 16 13" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.8735 0.0476074H1.125C0.503125 0.0476074 0 0.550727 0 1.17261V3.05542V4.55542V10.8773C0 11.4992 0.503125 12.0023 1.125 12.0023H14.875C15.4969 12.0023 16 11.4992 16 10.8773V4.55542V3.05542V1.17261C15.9985 0.550727 15.4937 0.0476074 14.8735 0.0476074ZM14.8735 10.8773H1.125V4.55542H14.8735V10.8773ZM1.125 1.17261V3.05542H14.8735V1.17261H1.125ZM3.70313 9.02417C4.08281 9.02417 4.39063 8.71637 4.39063 8.3367C4.39063 7.95697 4.08281 7.64917 3.70313 7.64917C3.32344 7.64917 3.01563 7.95697 3.01563 8.3367C3.01563 8.71637 3.32344 9.02417 3.70313 9.02417ZM6.9922 8.3367C6.9922 8.71637 6.6844 9.02417 6.30469 9.02417C5.925 9.02417 5.61719 8.71637 5.61719 8.3367C5.61719 7.95697 5.925 7.64917 6.30469 7.64917C6.6844 7.64917 6.9922 7.95697 6.9922 8.3367Z"/>
                                                </svg>
                                                Item Security
                                            </a>
                                        </li> -->
                                        <li class="<?= (isset($account_active)) ? $account_active : '';?>">
                                            <a href="<?= base_url('customer');?>">
                                                <svg width="18" height="19" viewBox="0 0 18 19" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9 0.0241699C4.02891 0.0241699 0 4.05308 0 9.02417C0 13.9953 4.02891 18.0242 9 18.0242C13.9711 18.0242 18 13.9953 18 9.02417C18 4.05308 13.9711 0.0241699 9 0.0241699ZM14.4686 14.4927C13.7584 15.2029 12.9305 15.7619 12.0094 16.1503C11.0566 16.5546 10.0441 16.7585 9 16.7585C7.95586 16.7585 6.94336 16.5546 5.99063 16.1521C5.06953 15.7619 4.24336 15.2046 3.53145 14.4945C2.82129 13.7843 2.2623 12.9564 1.87383 12.0353C1.46953 11.0808 1.26562 10.0683 1.26562 9.02417C1.26562 7.98003 1.46953 6.96753 1.87207 6.0148C2.2623 5.0937 2.81953 4.26753 3.52969 3.55562C4.23984 2.84546 5.06777 2.28647 5.98887 1.898C6.94336 1.4937 7.95586 1.28979 9 1.28979C10.0441 1.28979 11.0566 1.4937 12.0094 1.89624C12.9305 2.28647 13.7566 2.8437 14.4686 3.55386C15.1787 4.26401 15.7377 5.09194 16.1262 6.01304C16.5305 6.96753 16.7344 7.98003 16.7344 9.02417C16.7344 10.0683 16.5305 11.0808 16.1279 12.0335C15.7377 12.9546 15.1805 13.7826 14.4686 14.4927ZM9.63281 8.88354H12.8848C13.2346 8.88354 13.5176 9.16655 13.5176 9.51636C13.5176 9.86616 13.2346 10.1492 12.8848 10.1492H9.63281C8.9332 10.1492 8.36719 9.58315 8.36719 8.88354V4.66479C8.36719 4.31499 8.6502 4.03198 9 4.03198C9.3498 4.03198 9.63281 4.31499 9.63281 4.66479V8.88354Z" />
                                                </svg>
                                                <?= $this->lang->line('history');?>
                                            </a>
                                        </li>
                                        <li class="<?= (isset($favorite_active)) ? $favorite_active : '';?>">
                                            <a href="<?= base_url('customer/favorite') ?>">
                                                <svg width="18" height="16" viewBox="0 0 18 16" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.4089 1.64185C16.9024 2.13883 17.2905 2.72361 17.5627 3.37863C17.8437 4.05649 17.9859 4.77824 17.9842 5.52458C17.9789 7.156 17.2747 8.67502 16.0542 9.6918C15.3521 10.3204 14.2071 11.3669 13.0446 12.4292C12.9017 12.5599 12.7585 12.6908 12.6158 12.8212L12.4812 12.9442C11.2041 14.1115 10.0155 15.1979 9.4302 15.7187C9.31079 15.8259 9.1545 15.8856 8.99294 15.8856C8.83313 15.8856 8.67859 15.8259 8.55567 15.7187C7.71976 14.9759 5.67567 13.1074 4.03372 11.606L3.91113 11.4939C3.01069 10.671 2.30675 10.0276 2.05635 9.80244C2.00542 9.75502 1.9545 9.71112 1.90006 9.66546C0.705911 8.65395 0.0140085 7.15249 0.00171581 5.54215C-0.00530858 4.79932 0.135179 4.07756 0.416155 3.39619C0.686594 2.73941 1.07645 2.15288 1.57167 1.65239C2.5305 0.683024 3.78435 0.14917 5.10318 0.14917C6.60815 0.14917 8.0183 0.834048 8.99118 2.03522C9.96406 0.834048 11.3742 0.14917 12.8792 0.14917C14.1998 0.14917 15.4536 0.679511 16.4089 1.64185ZM12.8792 1.49609C11.7482 1.49609 10.6893 2.0317 9.97108 2.96595C9.73927 3.268 9.37225 3.44887 8.99293 3.44712C8.61186 3.44712 8.24659 3.268 8.01303 2.96595C7.29479 2.03346 6.2341 1.49961 5.10493 1.49961C4.14961 1.49961 3.23293 1.89122 2.52698 2.60419C2.1582 2.97473 1.86669 3.41551 1.65947 3.91424C1.44874 4.42702 1.34337 4.97141 1.34688 5.53336C1.35566 6.74507 1.87371 7.876 2.76932 8.63639C2.81713 8.67595 2.86184 8.71552 2.90491 8.75363L2.91332 8.76107C2.92737 8.77512 2.94142 8.78741 2.95547 8.7997C3.18903 9.00868 3.81596 9.58117 4.60971 10.3082L5.44035 11.0668C6.69771 12.2153 8.12015 13.5131 8.99293 14.3016C9.56542 13.7835 10.4277 12.9968 11.6921 11.8395C13.3094 10.3574 14.4772 9.29492 15.1603 8.68204L15.192 8.65395C15.6275 8.28868 15.9892 7.81805 16.2403 7.29297C16.5038 6.73629 16.6372 6.13746 16.639 5.51404C16.639 4.95034 16.5319 4.40419 16.3211 3.89317C16.1157 3.39795 15.8242 2.96068 15.4571 2.5919C14.7564 1.88595 13.8415 1.49609 12.8792 1.49609Z"/>
                                                </svg>
                                                <?= $this->lang->line('my_wishlist');?>
                                            </a>
                                        </li>
                                        <!--  <li class="<?= (isset($inventory_active)) ? $inventory_active : '';?>">
                                            <a href="<?= base_url('customer/inventory') ?>">
                                                <svg width="17" height="17" viewBox="0 0 17 17" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14.8735 1.01733H3.10791C2.4876 1.01733 1.98291 1.52202 1.98291 2.14233V13.8923C1.98291 14.5127 2.4876 15.0173 3.10791 15.0173H7.43604C7.74698 15.0173 7.99851 14.7658 7.99851 14.4548C7.99851 14.1439 7.74698 13.8923 7.43604 13.8923H3.10791V2.14233H14.8735V13.8923H13.561C13.2501 13.8923 12.9985 14.1439 12.9985 14.4548C12.9985 14.7658 13.2501 15.0173 13.561 15.0173H14.8735C15.4938 15.0173 15.9985 14.5127 15.9985 13.8923V2.14233C15.9985 1.52202 15.4938 1.01733 14.8735 1.01733Z"/>
                                                    <path d="M10.9985 14.4549C10.9985 14.6558 11.1058 14.8415 11.2798 14.942C11.4539 15.0425 11.6683 15.0425 11.8423 14.942C12.0163 14.8415 12.1235 14.6558 12.1235 14.4549C12.1235 14.2539 12.0163 14.0682 11.8423 13.9677C11.6683 13.8672 11.4539 13.8672 11.2798 13.9677C11.1058 14.0682 10.9985 14.2539 10.9985 14.4549Z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.56104 4.01733H9.43604C9.74697 4.01733 9.99851 4.26889 9.99851 4.57983C9.99851 4.89077 9.74697 5.14233 9.43604 5.14233H4.56104C4.2501 5.14233 3.99854 4.89077 3.99854 4.57983C3.99854 4.26889 4.2501 4.01733 4.56104 4.01733ZM10.9985 6.57983C10.9985 6.26889 11.2501 6.01733 11.561 6.01733C11.872 6.01733 12.1235 6.26889 12.1235 6.57983V12.4548C12.1235 12.7658 11.872 13.0173 11.561 13.0173C11.2501 13.0173 10.9985 12.7658 10.9985 12.4548V6.57983ZM7.43604 7.01733H4.56104C4.2501 7.01733 3.99854 7.26887 3.99854 7.57987C3.99854 7.8908 4.2501 8.14233 4.56104 8.14233H7.43604C7.74698 8.14233 7.99851 7.8908 7.99851 7.57987C7.99851 7.26887 7.74698 7.01733 7.43604 7.01733ZM4.56104 10.0173H7.43604C7.74698 10.0173 7.99851 10.2689 7.99851 10.5799C7.99851 10.8908 7.74698 11.1423 7.43604 11.1423H4.56104C4.2501 11.1423 3.99854 10.8908 3.99854 10.5799C3.99854 10.2689 4.2501 10.0173 4.56104 10.0173Z">
                                                </svg>
                                                My Inventory
                                            </a>
                                        </li>
                                        <li class="<?= (isset($payments_active)) ? $payments_active : '';?>">
                                            <a href="<?= base_url('user-payment') ?>">
                                                <svg width="17" height="17" viewBox="0 0 17 17" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14.8735 1.01733H3.10791C2.4876 1.01733 1.98291 1.52202 1.98291 2.14233V13.8923C1.98291 14.5127 2.4876 15.0173 3.10791 15.0173H7.43604C7.74698 15.0173 7.99851 14.7658 7.99851 14.4548C7.99851 14.1439 7.74698 13.8923 7.43604 13.8923H3.10791V2.14233H14.8735V13.8923H13.561C13.2501 13.8923 12.9985 14.1439 12.9985 14.4548C12.9985 14.7658 13.2501 15.0173 13.561 15.0173H14.8735C15.4938 15.0173 15.9985 14.5127 15.9985 13.8923V2.14233C15.9985 1.52202 15.4938 1.01733 14.8735 1.01733Z"/>
                                                    <path d="M10.9985 14.4549C10.9985 14.6558 11.1058 14.8415 11.2798 14.942C11.4539 15.0425 11.6683 15.0425 11.8423 14.942C12.0163 14.8415 12.1235 14.6558 12.1235 14.4549C12.1235 14.2539 12.0163 14.0682 11.8423 13.9677C11.6683 13.8672 11.4539 13.8672 11.2798 13.9677C11.1058 14.0682 10.9985 14.2539 10.9985 14.4549Z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.56104 4.01733H9.43604C9.74697 4.01733 9.99851 4.26889 9.99851 4.57983C9.99851 4.89077 9.74697 5.14233 9.43604 5.14233H4.56104C4.2501 5.14233 3.99854 4.89077 3.99854 4.57983C3.99854 4.26889 4.2501 4.01733 4.56104 4.01733ZM10.9985 6.57983C10.9985 6.26889 11.2501 6.01733 11.561 6.01733C11.872 6.01733 12.1235 6.26889 12.1235 6.57983V12.4548C12.1235 12.7658 11.872 13.0173 11.561 13.0173C11.2501 13.0173 10.9985 12.7658 10.9985 12.4548V6.57983ZM7.43604 7.01733H4.56104C4.2501 7.01733 3.99854 7.26887 3.99854 7.57987C3.99854 7.8908 4.2501 8.14233 4.56104 8.14233H7.43604C7.74698 8.14233 7.99851 7.8908 7.99851 7.57987C7.99851 7.26887 7.74698 7.01733 7.43604 7.01733ZM4.56104 10.0173H7.43604C7.74698 10.0173 7.99851 10.2689 7.99851 10.5799C7.99851 10.8908 7.74698 11.1423 7.43604 11.1423H4.56104C4.2501 11.1423 3.99854 10.8908 3.99854 10.5799C3.99854 10.2689 4.2501 10.0173 4.56104 10.0173Z">
                                                </svg>
                                                My Payable
                                            </a>
                                        </li>
                                        <li class="<?= (isset($document_active)) ? $document_active : '';?>">
                                            <a href="<?= base_url('customer/uploaded_docs') ?>">
                                                <svg width="17" height="17" viewBox="0 0 17 17" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14.8735 1.01733H3.10791C2.4876 1.01733 1.98291 1.52202 1.98291 2.14233V13.8923C1.98291 14.5127 2.4876 15.0173 3.10791 15.0173H7.43604C7.74698 15.0173 7.99851 14.7658 7.99851 14.4548C7.99851 14.1439 7.74698 13.8923 7.43604 13.8923H3.10791V2.14233H14.8735V13.8923H13.561C13.2501 13.8923 12.9985 14.1439 12.9985 14.4548C12.9985 14.7658 13.2501 15.0173 13.561 15.0173H14.8735C15.4938 15.0173 15.9985 14.5127 15.9985 13.8923V2.14233C15.9985 1.52202 15.4938 1.01733 14.8735 1.01733Z"/>
                                                    <path d="M10.9985 14.4549C10.9985 14.6558 11.1058 14.8415 11.2798 14.942C11.4539 15.0425 11.6683 15.0425 11.8423 14.942C12.0163 14.8415 12.1235 14.6558 12.1235 14.4549C12.1235 14.2539 12.0163 14.0682 11.8423 13.9677C11.6683 13.8672 11.4539 13.8672 11.2798 13.9677C11.1058 14.0682 10.9985 14.2539 10.9985 14.4549Z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.56104 4.01733H9.43604C9.74697 4.01733 9.99851 4.26889 9.99851 4.57983C9.99851 4.89077 9.74697 5.14233 9.43604 5.14233H4.56104C4.2501 5.14233 3.99854 4.89077 3.99854 4.57983C3.99854 4.26889 4.2501 4.01733 4.56104 4.01733ZM10.9985 6.57983C10.9985 6.26889 11.2501 6.01733 11.561 6.01733C11.872 6.01733 12.1235 6.26889 12.1235 6.57983V12.4548C12.1235 12.7658 11.872 13.0173 11.561 13.0173C11.2501 13.0173 10.9985 12.7658 10.9985 12.4548V6.57983ZM7.43604 7.01733H4.56104C4.2501 7.01733 3.99854 7.26887 3.99854 7.57987C3.99854 7.8908 4.2501 8.14233 4.56104 8.14233H7.43604C7.74698 8.14233 7.99851 7.8908 7.99851 7.57987C7.99851 7.26887 7.74698 7.01733 7.43604 7.01733ZM4.56104 10.0173H7.43604C7.74698 10.0173 7.99851 10.2689 7.99851 10.5799C7.99851 10.8908 7.74698 11.1423 7.43604 11.1423H4.56104C4.2501 11.1423 3.99854 10.8908 3.99854 10.5799C3.99854 10.2689 4.2501 10.0173 4.56104 10.0173Z">
                                                </svg>
                                                My Documents
                                            </a>
                                        </li> -->
                                        <li>
                                            <a href="<?php echo base_url('terms-conditions');?>">
                                                <svg width="17" height="17" viewBox="0 0 17 17" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14.8735 1.01733H3.10791C2.4876 1.01733 1.98291 1.52202 1.98291 2.14233V13.8923C1.98291 14.5127 2.4876 15.0173 3.10791 15.0173H7.43604C7.74698 15.0173 7.99851 14.7658 7.99851 14.4548C7.99851 14.1439 7.74698 13.8923 7.43604 13.8923H3.10791V2.14233H14.8735V13.8923H13.561C13.2501 13.8923 12.9985 14.1439 12.9985 14.4548C12.9985 14.7658 13.2501 15.0173 13.561 15.0173H14.8735C15.4938 15.0173 15.9985 14.5127 15.9985 13.8923V2.14233C15.9985 1.52202 15.4938 1.01733 14.8735 1.01733Z"/>
                                                    <path d="M10.9985 14.4549C10.9985 14.6558 11.1058 14.8415 11.2798 14.942C11.4539 15.0425 11.6683 15.0425 11.8423 14.942C12.0163 14.8415 12.1235 14.6558 12.1235 14.4549C12.1235 14.2539 12.0163 14.0682 11.8423 13.9677C11.6683 13.8672 11.4539 13.8672 11.2798 13.9677C11.1058 14.0682 10.9985 14.2539 10.9985 14.4549Z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.56104 4.01733H9.43604C9.74697 4.01733 9.99851 4.26889 9.99851 4.57983C9.99851 4.89077 9.74697 5.14233 9.43604 5.14233H4.56104C4.2501 5.14233 3.99854 4.89077 3.99854 4.57983C3.99854 4.26889 4.2501 4.01733 4.56104 4.01733ZM10.9985 6.57983C10.9985 6.26889 11.2501 6.01733 11.561 6.01733C11.872 6.01733 12.1235 6.26889 12.1235 6.57983V12.4548C12.1235 12.7658 11.872 13.0173 11.561 13.0173C11.2501 13.0173 10.9985 12.7658 10.9985 12.4548V6.57983ZM7.43604 7.01733H4.56104C4.2501 7.01733 3.99854 7.26887 3.99854 7.57987C3.99854 7.8908 4.2501 8.14233 4.56104 8.14233H7.43604C7.74698 8.14233 7.99851 7.8908 7.99851 7.57987C7.99851 7.26887 7.74698 7.01733 7.43604 7.01733ZM4.56104 10.0173H7.43604C7.74698 10.0173 7.99851 10.2689 7.99851 10.5799C7.99851 10.8908 7.74698 11.1423 7.43604 11.1423H4.56104C4.2501 11.1423 3.99854 10.8908 3.99854 10.5799C3.99854 10.2689 4.2501 10.0173 4.56104 10.0173Z">
                                                </svg>
                                                <?= $this->lang->line('terms_and_conditions');?>
                                            </a>
                                        </li>
                                        <li class="<?= (isset($sell_my_item)) ? $sell_my_item : '';?>">
                                            <a href="<?php echo base_url('customer/inventory');?>">
                                                <svg width="17" height="17" viewBox="0 0 17 17" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14.8735 1.01733H3.10791C2.4876 1.01733 1.98291 1.52202 1.98291 2.14233V13.8923C1.98291 14.5127 2.4876 15.0173 3.10791 15.0173H7.43604C7.74698 15.0173 7.99851 14.7658 7.99851 14.4548C7.99851 14.1439 7.74698 13.8923 7.43604 13.8923H3.10791V2.14233H14.8735V13.8923H13.561C13.2501 13.8923 12.9985 14.1439 12.9985 14.4548C12.9985 14.7658 13.2501 15.0173 13.561 15.0173H14.8735C15.4938 15.0173 15.9985 14.5127 15.9985 13.8923V2.14233C15.9985 1.52202 15.4938 1.01733 14.8735 1.01733Z"/>
                                                    <path d="M10.9985 14.4549C10.9985 14.6558 11.1058 14.8415 11.2798 14.942C11.4539 15.0425 11.6683 15.0425 11.8423 14.942C12.0163 14.8415 12.1235 14.6558 12.1235 14.4549C12.1235 14.2539 12.0163 14.0682 11.8423 13.9677C11.6683 13.8672 11.4539 13.8672 11.2798 13.9677C11.1058 14.0682 10.9985 14.2539 10.9985 14.4549Z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.56104 4.01733H9.43604C9.74697 4.01733 9.99851 4.26889 9.99851 4.57983C9.99851 4.89077 9.74697 5.14233 9.43604 5.14233H4.56104C4.2501 5.14233 3.99854 4.89077 3.99854 4.57983C3.99854 4.26889 4.2501 4.01733 4.56104 4.01733ZM10.9985 6.57983C10.9985 6.26889 11.2501 6.01733 11.561 6.01733C11.872 6.01733 12.1235 6.26889 12.1235 6.57983V12.4548C12.1235 12.7658 11.872 13.0173 11.561 13.0173C11.2501 13.0173 10.9985 12.7658 10.9985 12.4548V6.57983ZM7.43604 7.01733H4.56104C4.2501 7.01733 3.99854 7.26887 3.99854 7.57987C3.99854 7.8908 4.2501 8.14233 4.56104 8.14233H7.43604C7.74698 8.14233 7.99851 7.8908 7.99851 7.57987C7.99851 7.26887 7.74698 7.01733 7.43604 7.01733ZM4.56104 10.0173H7.43604C7.74698 10.0173 7.99851 10.2689 7.99851 10.5799C7.99851 10.8908 7.74698 11.1423 7.43604 11.1423H4.56104C4.2501 11.1423 3.99854 10.8908 3.99854 10.5799C3.99854 10.2689 4.2501 10.0173 4.56104 10.0173Z">
                                                </svg>
                                                <?= $this->lang->line('sell_my_item');?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('logout');?>">
                                                <svg width="18" height="19" viewBox="0 0 18 19" fill="#FF5E5E" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 6.77417C9.3498 6.77417 9.63281 6.49116 9.63281 6.14136V0.656982C9.63281 0.307178 9.3498 0.0241699 9 0.0241699C8.6502 0.0241699 8.36719 0.307178 8.36719 0.656982V6.14136C8.36719 6.49116 8.6502 6.77417 9 6.77417Z"/>
                                                    <path d="M9 18.0243C7.78535 18.0243 6.60586 17.787 5.49668 17.3176C4.42441 16.8641 3.46289 16.2137 2.63672 15.3876C1.81055 14.5614 1.16016 13.5999 0.706641 12.5276C0.237305 11.4184 0 10.2389 0 9.02427C0 7.59517 0.325195 6.22759 0.966797 4.96021C1.57852 3.75259 2.47324 2.68033 3.5543 1.85767C3.83203 1.64673 4.2293 1.69947 4.44023 1.9772C4.65117 2.25493 4.59844 2.6522 4.3207 2.86314C3.39258 3.56978 2.62266 4.49263 2.09707 5.52974C1.54512 6.62134 1.26562 7.79556 1.26562 9.02427C1.26562 10.0684 1.46953 11.0809 1.87383 12.0354C2.26406 12.9565 2.82129 13.7844 3.53145 14.4946C4.2416 15.2047 5.06953 15.7637 5.99063 16.1522C6.94336 16.5547 7.95586 16.7586 9 16.7586C10.0441 16.7586 11.0566 16.5547 12.0111 16.1504C12.9322 15.7602 13.7602 15.203 14.4703 14.4928C15.1805 13.7827 15.7395 12.9547 16.1279 12.0336C16.5305 11.0809 16.7344 10.0684 16.7344 9.02427C16.7344 7.79556 16.4549 6.62134 15.9029 5.53325C15.3773 4.49439 14.6074 3.57329 13.6793 2.86665C13.4016 2.65572 13.3471 2.25845 13.5598 1.98072C13.7707 1.70298 14.168 1.64849 14.4457 1.86118C15.525 2.68384 16.4197 3.75611 17.0332 4.96372C17.6748 6.23111 18 7.59693 18 9.02779C18 10.2424 17.7627 11.4219 17.2934 12.5311C16.8398 13.5999 16.1895 14.5614 15.3633 15.3876C14.5371 16.2137 13.5738 16.8624 12.5033 17.3159C11.3941 17.787 10.2146 18.0243 9 18.0243Z"/>
                                                </svg>
                                                <?= $this->lang->line('logout');?>
                                            </a>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        <?php else : ?>
                            <p class="m-0">
                                <a href="javascript:void(0)" id="loginClick" data-toggle="modal" data-target="#loginModal"><?= $this->lang->line('login_new');?></a>
                                <?= $this->lang->line('or');?>
                                <a href="javascript:void(0)" id="registerClick" data-toggle="modal" data-target="#registerModal"><?= $this->lang->line('register_new');?></a>
                            </p>
                        <?php endif; ?>
                    </div>


                </div>
            </div>
        </div>
    </header>
    <?php
    $active_auction_categories_header = $this->home_model->get_active_auction_categories();
    foreach ($active_auction_categories_header as $key => $value) {
        $count = 0;
        $auctions_online = $this->home_model->get_online_auctions($value['id']);
        if (!empty($auctions_online)) {
            $active_auction_categories_header[$key]['auction_id'] =  $auctions_online['id'];
            $active_auction_categories_header[$key]['expiry_time'] =  $auctions_online['expiry_time'];
            $count= $this->db->select('*')->from('auction_items')->where('auction_id',$auctions_online['id'])->where('sold_status','not')->where('auction_items.bid_start_time <',date('Y-m-d H:i'))->get()->result_array();
            $count = count($count);
        }
        if ($this->session->userdata('logged_in')) {
            $u_id = $this->session->userdata('logged_in')->id;
            $close_auctions = $this->db->where("FIND_IN_SET('".$u_id."', close_auction_users)")->get_where('auctions', ['expiry_time >=' => date('Y-m-d H:i:s'), 'status' => 'active','access_type' => 'closed','category_id' => $value['id']])->result_array();
            if ($close_auctions) {
                foreach ($close_auctions as $key1 => $close_auction) {
                    $item_ids = array();
                    $sub_total = count($this->lam->get_live_auction_items($close_auction['id'], 0, 0, $item_ids));
                    $count = $count + $sub_total;
                }
            }
        }
        $live_auctions = $this->db->get_where('auctions', ['expiry_time >=' => date('Y-m-d H:i:s'), 'status' => 'active','access_type' => 'live','category_id' => $value['id']])->result_array();
        if ($live_auctions) {
            foreach ($live_auctions as $key2 => $live_auction) {
                $live_item_ids = array();
                $total = count($this->lam->get_live_auction_items($live_auction['id'], 0, 0, $live_item_ids));
                $count = $count + $total;
            }
        }
        $active_auction_categories_header[$key]['item_count'] =  $count;
    }
    ?>
    <div class="navigation">
        <div class="container">
            <div class="list">
                <?php if($active_auction_categories_header):
                $j=0;
                ?>
                <?php foreach ($active_auction_categories_header as $key => $value):
                    $j++;

                    if (empty($headerCategoryId) && ($j == 1)) {
                        $this->session->set_userdata('categoryId',$value['id']);
                    }
                    $title = json_decode($value['title']);
                    $cat_id = '';
                    if (isset($category_id)) {
                        $cat_id = $category_id;
                    }
                    $activeClass = '';
                    if($this->uri->segment(1)=='search') {
                        if($value['id'] == $headerCategoryId){
                            $activeClass = 'active';
                        }else{
                            $activeClass = '';
                        }

                    };
                    ?>
                    <li id="<?=$value['id'];?>" class="<?= $activeClass;?>">
                        <!-- <?php //if (!empty($value['item_count'])) { ?> -->
                        <a href="<?= base_url('search/').$value['slug']; ?>"><?= $title->$language; ?></a>
                        <!-- <?php //}else{ ?>
                                    <?= $title->$language; ?>
                                <?php //} ?> -->
                    </li>

                <?php endforeach; ?>
            </div>
        </div>
        </li>
        <?php endif; ?>
    </div>
</div>
</div>
</div>

<!-- login -->
<div class="modal fade login-modal" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" >
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="row align-items-center">
                    <div  class="col-lg-6">
                        <div class="image text-center">
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/login-banner.png">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="content for-login">
                            <h2><?= $this->lang->line('sign_in_account'); ?></h2>
                            <form action="post">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                                <div class="form-group">
                                    <label><?= $this->lang->line('enter_email_address'); ?><span>*</span></label>
                                    <input type="text" placeholder="sample@hotmail.com" class="form-control" name="email">
                                    <span class="valid-error text-danger email-error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="input-pwd"><?= $this->lang->line('enter_password'); ?><span>*</span></label>
                                    <input type="password"
                                           oninput="this.value=this.value.replace(/[^0-9a-zA-Z!@#$%^&*()_-]/g,'');"
                                           placeholder="Password"
                                           class="form-control input-pwd"
                                           name="password1"
                                           id="input-pwd">
                                    <span class="valid-error text-danger password1-error"></span>
                                    <!-- <a href="javascript:;" class="toggle-password"> <i class="fa fa-eye-slash" aria-hidden="true"></i></a> -->
                                    <span toggle=".input-pwd" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                </div>
                                <div id='recaptchaLogin'></div>
                                <div class="button-row">
                                    <button type="button" id="loginVerify" class="btn btn-primary w-100" disabled="disabled"><?= $this->lang->line('sign_in'); ?></button>
                                </div>
                                <div style="margin-top: 8px">
                                    <span class=" text-success" id="error-msg" ></span>
                                </div>

                            </form>
                            <!-- <div class="login-with">
                              <div class="row">
                                  <div class="col-6 pr-0">
                                      <a href="#" class="btn btn-facebook">
                                          <img src="<?= NEW_ASSETS_USER; ?>/new/images/facebook-icon.png">
                                          Facebook
                                      </a>
                                  </div>
                                  <div class="col-6">
                                      <a href="#" class="btn btn-google">
                                          <img src="<?= NEW_ASSETS_USER; ?>/new/images/google-icon.png">
                                          Google
                                      </a>
                                  </div>
                              </div>
                          </div> -->
                            <div class="signup-link">
                                <a href="#" class="modal_custom-Link" id="forgotPassword" ><?= $this->lang->line('forgot_password'); ?></a>
                            </div>
                            <div class="signup-link">
                                <?= $this->lang->line('dont_have_account'); ?><a href="#" class="modal_custom-Link" id="loginToRegister" ><?= $this->lang->line('sign_up_now'); ?> </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="terms-condition">
                <?= $this->lang->line('sign_up_agree'); ?>
                <a
                        href="<?php echo base_url('terms-conditions');?>"ndata-toggle="modal" data-target="#termsModal"><?= $this->lang->line('terms_and_conditions'); ?> </a>
            </p>
        </div>
    </div>
</div>

<!-- /// verification code -->
<div class="modal fade login-modal" id="verificationModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="padding: 28px 33px;">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="row align-items-center">
                    <div  class="col-lg-6">
                        <div class="image text-center">
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/login-banner.png">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="content for-login">
                            <h2><?= $this->lang->line('enter_verification_code'); ?></h2>
                            <p id="one_time_password"><?= $this->lang->line('otp');?> <span id="nmbr"></span><?= $this->lang->line('otp2');?></p>
                            <form>
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                                <div class="form-group">
                                    <label><?= $this->lang->line('code'); ?><span>*</span></label>
                                    <input type="hidden" placeholder="" class="form-control" id="verifyPhone" value="" name="verifyPhone">
                                    <input type="text" placeholder="Enter verification code" class="form-control" name="codee">
                                    <span class="valid-error text-danger codee-error"></span>
                                    <!-- <span id="ragree-error" class="text-danger"></span> -->
                                </div>

                                <div class="button-row">
                                    <button type="button" id="confirm-verification2" class="btn btn-primary w-100">Submit</button>
                                </div>
                                <div class="note" id="resend_c" style="padding-top: 8px">
                                    <p style="color: #5c02b5"><?= $this->lang->line('otp_note');?>, <u><a href="javascript:void(0)" onclick="resendCode()"><?= $this->lang->line('request_code'); ?></a id=""></u></p>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- signup -->
<div class="modal fade login-modal" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="row align-items-center">
                    <div  class="col-lg-6">
                        <div class="image text-center">
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/login-banner.png">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="content for-signup">
                            <h2><?= $this->lang->line('sign_in_account'); ?></h2>
                            <form>
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <div class="form-group">
                                    <label><?= $this->lang->line('enter_name'); ?><span>*</span></label>
                                    <input type="text" oninput="this.value=this.value.replace(/[^aA-zZ0-9\s]/g,'');" placeholder="<?= $this->lang->line('register_full_name'); ?>" class="form-control" name="username">
                                    <span class="valid-error text-danger username-error"></span>
                                </div>
                                <div class="form-group">
                                    <label><?= $this->lang->line('enter_email_address'); ?><span>*</span></label>
                                    <input type="email" placeholder="sample@hotmail.com" class="form-control" name="email1">
                                    <span class="valid-error text-danger email1-error"></span>
                                </div>
                                <div class="form-group">
                                    <label><?= $this->lang->line('enter_mobile_num'); ?><span>*</span></label>
                                    <input type="tel" id="user-phone" name="phone" maxlength="9" oninput="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="50 123 4567" class="form-control" >
                                    <span class="valid-error text-danger phone-error"></span>
                                    <!-- <input type="Number" placeholder="+92" class="form-control" name="phone">
                                    <span class="valid-error text-danger phone-error"></span> -->
                                </div>
                                <div class="form-group">
                                    <label for="input-pwd"><?= $this->lang->line('create_password'); ?><span>*</span></label>
                                    <input type="password"
                                           placeholder="Password"
                                           class="form-control input-pwd"
                                           oninput="this.value=this.value.replace(/[^0-9a-zA-Z!@#$%^&*()_-]/g,'');"
                                           name="password"
                                           id="input-pwd">
                                    <!--  <a href="javascript:;" class="toggle-password"> <i class="fa fa-eye-slash" aria-hidden="true"></i></a> -->
                                    <span class="valid-error text-danger password-error"></span>
                                    <span toggle=".input-pwd" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                </div>
                                <div id='recaptchaRegister'></div>
                                <div class="button-row">
                                    <button type="button" id="register-verify" class="btn btn-primary w-100" disabled="disabled" onclick="gtag_report_conversion()"><?= $this->lang->line('sign_up'); ?></button>
                                </div>
                            </form>
                            <div class="signup-link">
                                <?= $this->lang->line('already_have_account'); ?> <a href="#" id="registerToLogin" class="modal_custom-Link"><?= $this->lang->line('login_new'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="terms-condition">
                <?= $this->lang->line('sign_up_agree');?>
                <a
                        href="<?php echo base_url('terms-conditions');?>"ndata-toggle="modal" data-target="#termsModal"><?= $this->lang->line('terms_and_conditions'); ?>.</a>
            </p>
        </div>
    </div>
</div>

<!--popup-->


<div class="modal fade bd-example-modal-lg" id="popup_data"  data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <?php
                if (isset($popup_data['popup_image']) && !empty($popup_data['popup_image']))
                {  $file = $this->db->get_where('files', ['id' => $popup_data['popup_image']])->row_array();  ?>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <img  class="img-responsive"  src="<?php echo base_url();?>../../uploads/popup/<?php echo (isset($file) && !empty($file['name'])) ? $file['name'] : 'default.png'; ?>">
                    <?php
                }?>

                <div class="row linkRow" style="margin:0px!important">
                    <div class="col-1">

                    </div>
                    <a class="col-4  cursorPointer" href="https://play.google.com/store/apps/details?id=com.pioneerauctions.app">
                        <div class="col-4  cursorPointer">
                            <h1></h1>

                        </div>
                    </a>
                    <div class="col-2">

                    </div>
                    <a class="col-4  cursorPointer" href="https://apps.apple.com/us/app/pioneer-online-auctions/id1533962112">
                        <div class="col-4  cursorPointer">
                            <h1></h1>

                        </div>
                    </a>
                    <div class="col-1">

                    </div>

                </div>


            </div>


        </div>
    </div>
</div>


<!-- Forgot password model -->
<div class="modal fade login-modal" id="forgotPasswordModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="padding: 28px 33px;">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="row align-items-center">
                    <div  class="col-lg-6">
                        <div class="image text-center">
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/login-banner.png">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="content for-login">
                            <h2><?= $this->lang->line('enter_email');?></h2>

                            <form>
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                                <div class="form-group">
                                    <label><?= $this->lang->line('email_address');?><span>*</span></label>
                                    <input  placeholder="<?= $this->lang->line('email');?>" class="form-control" id="" value="" name="email2">
                                    <span class="valid-error text-danger email2-error"></span>
                                </div>

                                <div class="button-row">
                                    <div class="button-row" id="error-msg_email" ></div>
                                    <button id="confirm-email"   class="btn btn-primary w-100"><?= $this->lang->line('submit');?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "80%";
        //Remove body scroll
        $('html, body').css({
            overflow: 'hidden',
            height: '100%'
        });
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0%";
    }
    $(document).ready(function(){
        $(".menu-toggler").click(function(){
            $(".back-effect").toggleClass("ad");
        });
        $(".close1").click(function(){
            $(".back-effect").removeClass("ad")
            //Add body scroll
            $('html, body').css({
                overflow: 'auto',
                height: '100%'
            });
        });

        setTimeout(function(){

            if (/Mobi/.test(navigator.userAgent)) {
                // mobile!

                <?php if (isset($popup_data) && !empty($popup_data)){
                if($popup_data['status'] == 1 ){

                ?>
                $('#popup_data').modal('show');
                <?php }
                }?>

            }else{

                <?php if (isset($popup_data2) && !empty($popup_data2)){
                if($popup_data2['status'] == 1 ){

                ?>
                $('#popup_data').modal('show');
                <?php }
                }?>
            }
        }, 500);



    });
</script>
