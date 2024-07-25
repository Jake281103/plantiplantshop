// Start Filter
let getwidgettitle = document.querySelector('.category-widget-titles');
let getwidget = document.querySelector('.category-widget');
let getwidgettitleicon = document.querySelector('.category-widget-title-icons');

getwidgettitle.addEventListener('click',function(){
  getwidget.classList.toggle('show');
  getwidgettitleicon.classList.toggle('fa-angle-down');
  getwidgettitleicon.classList.toggle('fa-angle-up');
});

let getsizewidgettitle = document.querySelector('.size-widget-titles');
let getsizewidget = document.querySelector('.size-widget');
let getsizewidgettitleicon = document.querySelector('.size-widget-title-icons');

getsizewidgettitle.addEventListener('click',function(){
  getsizewidget.classList.toggle('show');
  getsizewidgettitleicon.classList.toggle('fa-angle-down');
  getsizewidgettitleicon.classList.toggle('fa-angle-up');
});

let getpricewidgettitle = document.querySelector('.price-widget-titles');
let getpricewidget = document.querySelector('.price-widget');
let getpriceidgettitleicon = document.querySelector('.price-widget-title-icons');

getpricewidgettitle.addEventListener('click',function(){
  getpricewidget.classList.toggle('show');
  getpriceidgettitleicon.classList.toggle('fa-angle-down');
  getpriceidgettitleicon.classList.toggle('fa-angle-up');
});

let getpricerange = document.getElementById('pricerange');
let getmaxprice = document.getElementById('max-price');
getpricerange.addEventListener('change',function(){
  getmaxprice.innerText = getpricerange.value;
});

let getbtnclear = document.querySelector('.btnclears');
getbtnclear.addEventListener('click',function(){
  getmaxprice.innerHTML = '80';
});


let gettoolboxleft = document.querySelector('.toolbox-left');
let getclosebtn = document.querySelector('.closebtns');
let getsidebarfilter = document.querySelector('.sidebar-filter');

gettoolboxleft.addEventListener('click',function(){
  getsidebarfilter.classList.add('show');
});

getclosebtn.addEventListener('click',function(){
  getsidebarfilter.classList.remove('show');
});
// End Filter