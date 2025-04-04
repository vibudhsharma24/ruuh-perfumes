<style>
    #sidebar {
        width: 100%;
        /* Even though it's in a col-3, we want the sidebar to take full width within that column */
        background-color: #023047;
        /* Darker background color */
        box-shadow: 4px 0 8px rgba(0, 0, 0, 0.2);
        /* Subtle shadow on the right */
        z-index: 1000;
        /* Ensure sidebar is above other content */
        padding-top: 20px;
        /* Space from the top */
    }

    #sidebar img {
        width: 150px;
        /* Adjust logo size as needed */
        display: block;
        /* Makes the image a block element */
        margin-left: auto;
        margin-right: auto;
        /* Center the logo */
        margin-bottom: 40px;
        /* Space below the logo */
    }

    #sidebar h4 {
        font-size: 24px;
        /* Adjust heading size as needed */
        margin-bottom: 40px;
        /* Space below the heading */
        color: #e9ecef;
        /* Light text color */
        text-align: center;
        /* Center the heading */
        font-weight: 600;
        /* Make the heading bolder */
    }

    #sidebar .list-group-item {
        border: 0;
        /* Remove default border */
        border-radius: 8px;
        /* Rounded corners for each item */
        margin: 5px auto;
        /* Vertical space between items, centered horizontally */
        width: 90%;
        /* Adjust width as needed */
        padding: 12px 20px;
        /* More padding */
        font-size: 18px;
        /* Larger font size */
        background-color: transparent;
        /* No background color */
        color: #e9ecef;
        /* Light text color */
        transition: all 0.3s ease;
        /* Smooth transition for hover effect */
    }

    #sidebar .list-group-item:hover {
        background-color: #025ea1;
        /* Darker shade on hover */
        color: #fff;
        /* White text on hover */
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        /* Subtle shadow on hover */
    }

    #sidebar .list-group-item.active {
        background-color: #0284c7;
        /* Primary color for active item */
        color: #fff;
        /* White text */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        /* Subtle shadow for active item */
    }

    #sidebar a {
        text-decoration: none;
        /* Remove underline */
        color: inherit;
        /* Inherit text color from parent */
        display: block;
        /* Make the link fill the list item */
    }

    #sidebar svg {
        margin-right: 15px;
        /* Space between icon and text */
        vertical-align: middle;
        /* Align icon with text */
        width: 24px;
        /* Fixed width for icons */
        height: 24px;
        /* Fixed height for icons */
    }

    /* Styling for the logout button */
    /* #sidebar .logout {
        margin-top: 20px;
        text-align: center;

    }

    #sidebar .logout .btn-danger {
        width: 90%;

        padding: 10px 20px;
        font-size: 18px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    #sidebar .logout .btn-danger:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    #sidebar .logout svg {
        margin-left: 10px;
    } */
</style>
<!-- Sidebar -->
<div id="sidebar" class="col-2 d-flex flex-column text-white vh-100 p-0">
    <div class="text-center py-3">
        <img src="./assets/images/logo.png" alt="Logo" class="img-fluid mb-4 mt-4" style="width: 200px;">
        <!-- <h4>Amba Associates</h4> -->
    </div>
    <ul class="list-group list-group-flush">
        <a href="revenue.php">
            <li class="list-group-item <?php echo basename($_SERVER['PHP_SELF']) == './revenue.php' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-currency-rupee" viewBox="0 0 16 16">
                    <path
                        d="M4 3.06h2.726c1.22 0 2.12.575 2.325 1.724H4v1.051h5.051C8.855 7.001 8 7.558 6.788 7.558H4v1.317L8.437 14h2.11L6.095 8.884h.855c2.316-.018 3.465-1.476 3.688-3.049H12V4.784h-1.345c-.08-.778-.357-1.335-.793-1.732H12V2H4z" />
                </svg><span>Revenue</span>
            </li>
        </a>
        <a href="orders.php">
            <li class="list-group-item <?php echo basename($_SERVER['PHP_SELF']) == './orders.php' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cart"
                    viewBox="0 0 16 16">
                    <path
                        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                </svg><span>Orders</span>
            </li>
        </a>
        <a href="logistics.php">
            <li
                class="list-group-item <?php echo basename($_SERVER['PHP_SELF']) == './logistics.php' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-truck"
                    viewBox="0 0 16 16">
                    <path
                        d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                </svg><span>Logistics</span>
            </li>
        </a>
        <a href="stock.php">
            <li class="list-group-item <?php echo basename($_SERVER['PHP_SELF']) == './stock.php' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-boxes"
                    viewBox="0 0 16 16">
                    <path
                        d="M7.752.066a.5.5 0 0 1 .496 0l3.75 2.143a.5.5 0 0 1 .252.434v3.995l3.498 2A.5.5 0 0 1 16 9.07v4.286a.5.5 0 0 1-.252.434l-3.75 2.143a.5.5 0 0 1-.496 0l-3.502-2-3.502 2.001a.5.5 0 0 1-.496 0l-3.75-2.143A.5.5 0 0 1 0 13.357V9.071a.5.5 0 0 1 .252-.434L3.75 6.638V2.643a.5.5 0 0 1 .252-.434zM4.25 7.504 1.508 9.071l2.742 1.567 2.742-1.567zM7.5 9.933l-2.75 1.571v3.134l2.75-1.571zm1 3.134 2.75 1.571v-3.134L8.5 9.933zm.508-3.996 2.742 1.567 2.742-1.567-2.742-1.567zm2.242-2.433V3.504L8.5 5.076V8.21zM7.5 8.21V5.076L4.75 3.504v3.134zM5.258 2.643 8 4.21l2.742-1.567L8 1.076zM15 9.933l-2.75 1.571v3.134L15 13.067zM3.75 14.638v-3.134L1 9.933v3.134z" />
                </svg>Stock</span>
            </li>
        </a>
        <a href="product.php">
            <li class="list-group-item <?php echo basename($_SERVER['PHP_SELF']) == './product.php' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-grid"
                    viewBox="0 0 16 16">
                    <path
                        d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z" />
                </svg>Products</span>
            </li>
        </a>
        <a href="supplier.php">
            <li class="list-group-item <?php echo basename($_SERVER['PHP_SELF']) == './supplier.php' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-person-vcard" viewBox="0 0 16 16">
                    <path
                        d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4m4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5M9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8m1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5" />
                    <path
                        d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96q.04-.245.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 1 1 12z" />
                </svg>Suppliers</span>
            </li>
        </a>
       
    </ul>

    <!-- <div class="logout">
        <a href="logout.php"><button class="btn btn-danger">Logout <svg xmlns="http://www.w3.org/2000/svg" width="16"
                    height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                    <path fill-rule="evenodd"
                        d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                </svg></button></a>
    </div> -->
</div>