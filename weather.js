document.addEventListener("DOMContentLoaded", function () {
const clickbtn = document.getElementById("touch");
function details(info){
  var location = document.getElementById("names");
  location.textContent = info.name;
  var humid = document.getElementById("hum");
  humid.textContent = info.main["humidity"] + "%";
  var date= document.getElementById("date");
  var time= document.getElementById("time");
  var localTimestamp = info.dt+info.timezone;
  const localTime = new Date(localTimestamp * 1000);
  const options = { year: '2-digit', month: '2-digit', day: '2-digit'} ;
  const option={hour: '2-digit', minute: '2-digit' };
  const formattedDate = localTime.toLocaleString('en-US', options);
  const formattedTime = localTime.toLocaleString('en-US', option);
  date.textContent= formattedDate;
  time.textContent= formattedTime;
  var describe = document.getElementById('des');
  describe.textContent = info.weather[0].description;
  var tempCelsius = document.getElementById("temps");
  var calcCelsius=info.main['temp'] - 273.15;
  tempCelsius.textContent= calcCelsius.toFixed(2)+"°C";
  var presDet= document.getElementById("press");
  presDet.textContent=info.main['pressure']+ " hPa";
  var air= document.getElementById("wind")
  air.textContent=info.wind['speed'] +" m/s"
  var iconCode = info.weather[0].main;
  if (iconCode =="Clouds"){
  var weatherIcon = document.getElementById("code");
  weatherIcon.src = "cloud.png";
  }
  else if (iconCode=="Rain"){
  var weatherIcon = document.getElementById("code");
  weatherIcon.src = "rain.png";
  }
  else if (iconCode=="Thunderstorm"){
  var weatherIcon = document.getElementById("code");
  weatherIcon.src = "storm.png";
  }
  else if (iconCode=="Drizzle"){
  var weatherIcon = document.getElementById("code");
  weatherIcon.src = "drizzle.png";
  }
  else if (iconCode=="Clear"){
  var weatherIcon = document.getElementById("code");
  weatherIcon.src = "clear.png";   
  }
  else if (iconCode=="Mist"){
  var weatherIcon = document.getElementById("code");
  weatherIcon.src = "visibility.png"; 
  }
  var temptwo=document.getElementById('minmax');
  var minimum=info.main['temp_min']-273.15;
  var maximum=info.main['temp_max']-273.15;
  temptwo.textContent=minimum.toFixed(2)+"°C /" + maximum.toFixed(2)+"°C";

  var visible=document.getElementById('vis');
  visible.textContent=info.visibility+" m";
}


async function DataFetch() {
  const response = await fetch(
    "https://api.openweathermap.org/data/2.5/weather?q=BhimDatta&appid=5b6efc31d9df5b58c06a64f0cba78094"
  );
  const data = await response.json();
  console.log(data);
  details(data);
}

async function FetchData() {
  const searchQuery = document.getElementById("input-box").value;
  console.log(searchQuery);

  const response = await fetch(
    `https://api.openweathermap.org/data/2.5/weather?q=${searchQuery}&appid=5b6efc31d9df5b58c06a64f0cba78094`
  );

  const data= await response.json();
  if (response.status==200){
  console.log(data) ;
  details(data); }
  
  if(response.status === 404) {
    var loopContainer = document.getElementById("container");
    loopContainer.innerHTML = "";
    console.log("Location not found");
    var not = document.createElement("p1");
    not.textContent = "NO RESULT FOUND.";
    loopContainer.appendChild(not);
  }
}


if (document.getElementById("input-box").value == "") {
  DataFetch();
} else {
  FetchData();
}

clickbtn.addEventListener("click", () => FetchData());
});