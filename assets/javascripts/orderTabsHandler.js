if (window.location.href.endsWith('orders')) {
    let tabButtons = document.querySelectorAll('.tabs-btn');
    // let orderLists = document.querySelectorAll('.order-list');

    tabButtons[0].addEventListener('click', (e) => openTab(e, 'pending'));
    tabButtons[1].addEventListener('click', (e) => openTab(e, 'inProgress'));
    tabButtons[2].addEventListener('click', (e) => openTab(e, 'archive'));

    tabButtons[0].click();

    function openTab(evt, tabName) {
        var i, orderLists, tabBtns;

        orderLists = document.getElementsByClassName("order-list");
        for (i = 0; i < orderLists.length; i++) {
            orderLists[i].style.display = "none";
        }

        tabBtns = document.getElementsByClassName("tabs-btn");
        for (i = 0; i < tabBtns.length; i++) {
            tabBtns[i].className = tabBtns[i].className.replace(" btn-active", "");
        }

        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " btn-active";
    }
}


