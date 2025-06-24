const datepicker = document.querySelector(".datepicker");
const rangeInput = datepicker.querySelector('input');
const calendarContainer = datepicker.querySelector(".calendar");
const leftCalendar = datepicker.querySelector('.left-side');
const rightCalendar = datepicker.querySelector('.right-side');


let leftDate = new Date();
let rightDate = new Date(leftDate);
rightDate.setMonth(rightDate.getMonth()+1);

calendarContainer.hidden = false;                                   //Test purpose -> delete in the end

const formatDate = (date) => {                                          //Date format
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');    
    const d = String(date.getDate()).padStart(2, '0');
    return `${d}-${m}-${y}`;
}


const createDateElement = (date, isDisabled, isToday) => {
    const span = document.createElement("span");
    span.textContent = date.getDate();
    span.classList.toggle('disabled', isDisabled);
    if (!isDisabled) {
        span.classList.toggle("today", isToday);
    }
    return span;
}






const renderCalndar = (calendar, year, month) => {
    const label = calendar.querySelector('.label');
    label.textContent = new Date(year, month).toLocaleString(
        navigator.language || "en-US",                              //Change language here
    {    
        year: 'numeric',
        month: 'long',
    });
    const datesContainer = calendar.querySelector(".dates");
    datesContainer.innerHTML = '';

    const startDate = new Date(year, month, 1);
    startDate.setDate(startDate.getDate() - startDate.getDay());

    const endDate = new Date(startDate);
    endDate.setDate(endDate.getDate() + 42);

    const fragment = document.createDocumentFragment();
    while (startDate < endDate) {
        const isDisabled = startDate.getMonth() != month;      
        const isToday = formatDate(startDate) == formatDate(new Date());        
        const dateElement = createDateElement(startDate, isDisabled, isToday);
        fragment.appendChild(dateElement);
        startDate.setDate(startDate.getDate() + 1);
    }

    datesContainer.appendChild(fragment);
};



renderCalndar(leftCalendar, leftDate.getFullYear(), leftDate.getMonth());
renderCalndar(rightCalendar, rightDate.getFullYear(), rightDate.getMonth());





rangeInput.addEventListener("focus", () => {
    calendarContainer.hidden = false;
})

document.addEventListener("click", (event) => {
    if (!datepicker.contains(event.target)) {
        calendarContainer.hidden = true;
    }
});