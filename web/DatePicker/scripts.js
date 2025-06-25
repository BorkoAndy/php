const dateStringFormat = 'ru';   //Change here to change the displayed format of date

const datepicker = document.querySelector(".datepicker");
const rangeInput = datepicker.querySelector('input');
const calendarContainer = datepicker.querySelector(".calendar");
const leftCalendar = datepicker.querySelector('.left-side');
const rightCalendar = datepicker.querySelector('.right-side');
const prevButton = datepicker.querySelector(".prev");
const nextButton = datepicker.querySelector(".next");
const selectionElement = datepicker.querySelector('.selection');
const applyButton = datepicker.querySelector(".apply");
const cancelButton = datepicker.querySelector(".cancel");




let start = null;
let end = null;
let originalStart = null;
let originalEnd = null;

let leftDate = new Date();
leftDate.setDate(1);
let rightDate = new Date(leftDate);
rightDate.setMonth(rightDate.getMonth()+1);
                               

const formatDate = (date) => {                                          //Date format
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');    
    const d = String(date.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
}


const createDateElement = (date, isDisabled, isToday) => {
    const span = document.createElement("span");
    span.textContent = date.getDate();
    span.classList.toggle('disabled', isDisabled);
    if (!isDisabled) {
        span.classList.toggle("today", isToday);
        span.setAttribute("data-date", formatDate(date));
    }

    span.addEventListener('click', handleDateClick)
    span.addEventListener('mouseover', handleDateMouseOver)

    return span;
};


const displaySelection = () => {
    if(start && end) {
        const startDate = start.toLocaleDateString(dateStringFormat);
        const endDate = end.toLocaleDateString(dateStringFormat);

        selectionElement.textContent = `${startDate} - ${endDate}`
    }
};



const applyHightlighting = () => {
    const dateElements = datepicker.querySelectorAll('span[data-date]');
    for (const dateElement of dateElements){
        dateElement.classList.remove('range-start', 'range-end', 'in-range');
    }

    if(start){
        const startDate = formatDate(start);
        const startElement = datepicker.querySelector(`span[data-date="${startDate}"]`);
        if(startElement){
            startElement.classList.add("range-start");
            if(!end) startElement.classList.add("range-end");
        }
    }

    if(end){
        const endDate = formatDate(end);
        const endElement = datepicker.querySelector(`span[data-date="${endDate}"]`);
        if(endElement)
            endElement.classList.add("range-start");
    }

    if(start && end) {
        for (const dateElement of dateElements){
            const date = new Date(dateElement.dataset.date);
            if(date > start && date < end) {
                dateElement.classList.add("in-range");
            }
        }
    }
};


const handleDateMouseOver = (event) => {
    const hoverElement = event.target;
    if(start && !end){
        applyHightlighting();
        const hoverDate = new Date(hoverElement.dataset.date);
        datepicker.querySelectorAll('span[data-date]').forEach((dateElement) => {
            const date = new Date(dateElement.dataset.date);
            if(date > start && date < hoverDate && start < hoverDate){
                dateElement.classList.add("in-range");
            }
        })
    }
}

const handleDateClick = (event) => {
    const dateElement = event.target;
    const selectedDate = new Date(dateElement.dataset.date);
    if(!start || (start && end)){
        start = selectedDate;
        end = null;
    }else if (selectedDate < start){
        start = selectedDate;
    }else{
        end = selectedDate;
    }
    applyHightlighting();
    displaySelection();
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
    applyHightlighting();
};


const updatCalendars = () => {
    renderCalndar(leftCalendar, leftDate.getFullYear(), leftDate.getMonth());
    renderCalndar(rightCalendar, rightDate.getFullYear(), rightDate.getMonth());
};





rangeInput.addEventListener("focus", () => {
    originalStart = start;
    originalEnd = end;
    calendarContainer.hidden = false;
})

document.addEventListener("click", (event) => {
    if (!datepicker.contains(event.target)) {
        calendarContainer.hidden = true;
    }
});

// previous button navigation
prevButton.addEventListener("click", () => {
    leftDate.setMonth(leftDate.getMonth() - 1);
    rightDate.setMonth(rightDate.getMonth() - 1);
    updatCalendars();
});

// next button navigation
nextButton.addEventListener("click", () => {
    leftDate.setMonth(leftDate.getMonth() + 1);
    rightDate.setMonth(rightDate.getMonth() + 1);
    updatCalendars();
})

applyButton.addEventListener('click', () => {
      if(start && end) {
        const startDate = start.toLocaleDateString(dateStringFormat);
        const endDate = end.toLocaleDateString(dateStringFormat);
        rangeInput.value = `${startDate} - ${endDate}`;
        calendarContainer.hidden = true;
    }
}); 

cancelButton.addEventListener('click', () => {
    start = originalStart;
    end = originalEnd;
    applyHightlighting();
    displaySelection();
    calendarContainer.hidden = true;

}); 

//initialize the datepicker
updatCalendars();