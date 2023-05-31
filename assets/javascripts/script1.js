if (document.querySelector('.auth-user-nav')) {
    let headerUserBtn = document.querySelector('.signBtn');
    let authUserNavBar = document.querySelector('.auth-user-nav');

    headerUserBtn.addEventListener('mouseover', function () {
        authUserNavBar.style.visibility = 'unset';
    })

    headerUserBtn.addEventListener('mouseout', function () {
        authUserNavBar.style.visibility = 'hidden';
    })

    authUserNavBar.addEventListener('mouseover', function () {
        authUserNavBar.style.visibility = 'unset';
    })

    authUserNavBar.addEventListener('mouseout', function () {
        authUserNavBar.style.visibility = 'hidden';
    })
}

if (document.querySelector('.search-section')) {
    let searchBarHint = document.querySelector('.search-section p a');
    let searchBarInput = document.querySelector('.form-input-section input');

    searchBarHint.addEventListener('click', function () {
        searchBarInput.value = searchBarHint.textContent;
    })
}