if (window.location.href === 'https://localhost:8000/client/create_order') {
    // order form section
    let currentTab = 0;
    showTab(currentTab);

    console.log(window.location.href);

    let prevButton = document.getElementById('prevBtn');
    let nextButton = document.getElementById('nextBtn');

    prevButton.addEventListener('click', () => nextPrev(-1));
    nextButton.addEventListener('click', () => nextPrev(1));


    function showTab(n) {
        console.log(n);
        console.log(currentTab);

        let x = document.getElementsByClassName('tab');
        x[n].style.display = "block";

        if (n === 0) {
            document.getElementById('prevBtn').style.display = 'none';
            document.getElementById('nextBtn').style.width = '100%';
        } else {
            document.getElementById('prevBtn').style.display = 'block';
            document.getElementById('nextBtn').style.width = '69%';
        }
        if (n === (x.length - 1)) {
            document.getElementById('nextBtn').innerHTML = 'Создать заявку';
        } else {
            document.getElementById('nextBtn').innerHTML = 'Далее';
        }
    }

    function nextPrev(n) {
        let x = document.getElementsByClassName('tab');

        x[currentTab].style.display = 'none';
        currentTab += n;

        if (currentTab >= x.length) {
            document.getElementById('order-form').submit();
            return false;
        }

        showTab(currentTab);
    }
}