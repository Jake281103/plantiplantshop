// Start Right Navbar
const getsitesettings = document.getElementById('sitesettings');

    getsitesettings.addEventListener('click',function(){
        document.body.classList.toggle('show-nav');
    });
// End Right Navbar

// Start Navbar
    // start top navbar
        function dropbtn(e){
            // console.log(e.target);
            // console.log(e.target.parentElement.nextElementSibling);
            e.target.parentElement.nextElementSibling.classList.toggle('show');
        }
    // end top navbar
// End Navbar

$(document).ready(function(){

    // Start Navbar
        // start left sidebar
            $('.sidebarlinks').click(function(){
                $('.sidebarlinks').removeClass('currents');
                $(this).addClass('currents');
            });
        // end left sidebar
    // End Navbar

});


// Start Footer
const getyear = document.getElementById('getyear');
const getfullyear = new Date().getFullYear();
getyear.textContent = getfullyear;
// End Footer


