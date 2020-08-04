let hotelButton = document.querySelector("#getHotelBtn");
let responseBlock = document.querySelector("#response_block");
let hotelListDOM = document.querySelector(".hotelList");

/**
 *
 * @param {array} $data hotel object of rhe response
 *
 */

function display_result($data) { 
    // create a DOM 
    $data  = $data || {}; 

    //Hide the block first  
    responseBlock.classList.remove('d-none');
    let hotelList; 

    if(!$data)
      hotelList = fetch_hotel_list(localStorage.getItem('result')); 
    else 
      hotelList = fetch_hotel_list($data); 

    console.log(`Total hotel list is ${hotelList.length}`); 

    
    /**
     * if there is a responses then 
     * we need to empty the array for the 
     * new response 
     */
    if( responsesDOM.length > 0 )
      responsesDOM = []; 
      
    hotelList.map( hotel => {
        _prepare_list(hotel.HotelInfo);  
    }); 

    let content  = responsesDOM.map((el) => el.innerHTML); 

    responseBlock.innerHTML =content.join(' '); 
}

  /**
   * if there is a responses then
   * we need to empty the array for the
   * new response
   */

  if (responsesDOM.length > 0) responsesDOM = [];

  hotelList.map((hotel) => {
    _prepare_list(hotel.HotelInfo);
  });

  let content = responsesDOM.map((el) => el.innerHTML);

  responseBlock.innerHTML = content.join(" ");
}

let responsesDOM = [];
/**
 *
 * @param {object} $data HotelInfo object from a sabre response
 * @returns void
 */
function _prepare_list($data) {
  let $el = hotelListDOM.cloneNode(true);
  $el.querySelector("#hotel_title").innerHTML = $data.HotelName;
  $el.querySelector("#sabre_rate").innerHTML = "Rate " + $data.SabreRating;
  $el.querySelector("#address_line").innerHTML =
    $data.LocationInfo.Address.AddressLine1;

  $el.querySelector("#hotel_logo").setAttribute("src", $data.Logo);
  let origin = [
    $data.LocationInfo.Address.CityName.value,
    $data.LocationInfo.Address.CountryName.value,
  ];

  $el.querySelector("#origin").innerHTML = origin.join(" , ");

  let listItem;

  for (contact in $data.LocationInfo.Contact) {
    listItem = document.createElement("li");
    listItem.innerHTML = `${contact}: ${$data.LocationInfo.Contact[contact]}`;
    $el.querySelector("#contact_info").append(listItem);
  }
  responsesDOM.push($el);
}

/**
 *
 * @param {string} $data encoded JSON string
 * @return {array} hotel list
 */
function fetch_hotel_list($data) {
  $data = $data || {};
  //we assume that all the inputs are
  // JSON encoded string
  let data = JSON.parse($data);
  return data.GetHotelAvailRS.HotelAvailInfos.HotelAvailInfo;
}

async function get_hotel_list() {
  await axios({
    url: "./ajax.php",
    data: {
      hotel: "getHotel",
    },
    method: "POST",
  })
    .then((response) => {
      localStorage.setItem("result", response.data);
      display_result(response.data);
    })
    .catch((err) => {
      console.log(err);
    });
}

// this will fire the ajax request 
// hotelButton.addEventListener("click", get_hotel_list);
