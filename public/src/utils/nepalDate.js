// public/js/nepalDate.js

const nepaliMonths = [
    "बैशाख", "जेठ", "असार", "साउन", "भदौ", "आश्विन", "कार्तिक", "मंसिर", "पौष", "माघ", "फाल्गुण", "चैत"
];

const nepaliDays = [
    "आइतबार", "सोमबार", "मंगलबार", "बुधबार", "बिहिबार", "शुक्रबार", "शनिबार"
];

// Convert digits to Nepali
function toNepaliDigits(number) {
    return number.toString().replace(/\d/g, d => '०१२३४५६७८९'[d]);
}

// Function to get Nepali date string with time
function getNepaliDateTime() {
    const now = new Date();
    const nepDate = NepaliDate.fromAD(now.getFullYear(), now.getMonth() + 1, now.getDate());

    const dayName = nepaliDays[nepDate.getDay()];
    const date = toNepaliDigits(nepDate.getDate());
    const month = nepaliMonths[nepDate.getMonth()];
    const year = toNepaliDigits(nepDate.getYear());

    const hours = toNepaliDigits(now.getHours());
    const minutes = toNepaliDigits(now.getMinutes());
    const seconds = toNepaliDigits(now.getSeconds());

    return `${dayName}, ${date} ${month} ${year} ${hours}:${minutes}:${seconds}`;
}

// Function to update an element with Nepali date
function updateNepaliDateTime(elementId, interval = 1000) {
    const el = document.getElementById(elementId);
    if (!el) return;

    const update = () => {
        el.innerHTML = `<i class="bi bi-calendar3 me-1"></i> ${getNepaliDateTime()}`;
    }

    update(); // initial call
    setInterval(update, interval);
}