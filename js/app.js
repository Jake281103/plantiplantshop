// Start Header Section

//Start Header Button Section
const mobileNav = document.querySelector(".hamburger");
const navbar = document.querySelector(".menubar");

const toggleNav = () => {
  navbar.classList.toggle("active");
  mobileNav.classList.toggle("hamburger-active");
};
mobileNav.addEventListener("click", () => toggleNav());
//End Header Button Section

$(document).ready(function () {
  // Section Header Scroll
  $(window).scroll(function () {
    let getscrolltop = $(this).scrollTop();
    // console.log(getscrolltop);

    if (getscrolltop >= 65) {
      $(".headerbottoms").addClass("fixed-top nav-scroll");
    } else {
      $(".headerbottoms").removeClass("fixed-top nav-scroll");
    }
  });
  // End Header Section

  // Start Back to Top
  $(window).scroll(function () {
    let getscrolltop = $(this).scrollTop();
    // console.log(getscrolltop);

    if (getscrolltop >= 497) {
      $(".btn-backtotops").fadeIn(1000);
    } else {
      $(".btn-backtotops").fadeOut(1000);
    }
  });
  // End Back to Top

  // Start Featured Products Section
  $(".propertylists").click(function () {
    // $(this).addClass('activeitems');

    $(this).addClass("activeitems").siblings().removeClass("activeitems");
  });
  // End Featured Products Section

  // Start Footer Section
  const getyear = document.getElementById("getyear");
  const getfullyear = new Date().getFullYear();
  getyear.innerHTML = getfullyear;
  // End Footer Section

  // Start Modal Box
  $(".formlinks").click(function () {
    // $(this).addClass('activeitems');

    $(this).addClass("activeforms").siblings().removeClass("activeforms");

    if ($(this).hasClass("signin-btn")) {
      $(".signin").show();
      $(".register").hide();
    } else {
      $(".register").show();
      $(".signin").hide();
    }
  });

  // End Modal Box

  // Start Cart

    $("#formA input[type=radio]").click(function () {
      // alert(this.value);

      let subtotal = parseFloat($('#subtotal').val());
      let shipping = parseFloat(this.value);
      var totalprice = subtotal + shipping;
      totalprice = parseFloat(totalprice).toFixed(2);
      $('#totalprice').text("$"+totalprice);
      $("#total").val(totalprice);
    });

  // End Cart

});
