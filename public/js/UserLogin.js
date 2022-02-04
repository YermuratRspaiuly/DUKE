function genURL(url) {
    const ORIGIN = location.origin
    return ORIGIN + url
}


async function SendUserData(url = '', data = {}) {
     mask.classList.remove('hide')
     document.querySelector('#success').innerText = 'Подождите....'
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })

    return await response.json()
}

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function error(errorText,id){
    mask.classList.add('hide')
    document.querySelector(`#reg_${id}`).innerText = errorText
    setTimeout(()=>{
        document.querySelector(`#reg_${id}`).innerText = ''
    }, 5000)
}
var mask = document.querySelector('.mask')


let log_login = document.querySelector('.login input[name="login"]')
let log_password = document.querySelector('.login input[name="password"]')
var l_login = ''
var l_password = ''

log_login.addEventListener('change', (event) => {
    console.log('h1');
    l_login = event.target.value
})

log_password.addEventListener('change', (event) => {
    l_password = event.target.value
})

let sendLogin = document.querySelector('.login input[type="submit"]')

sendLogin.addEventListener('click', () => {

    if(l_login.length <= 0) {
        error('Логин не может быть пустым', '1')
        return;
    }

    if(l_password.length <= 0) {
        error('Вы не ввели пароль', '2')
        return;
    }

    SendUserData(genURL('/signin'), { login: l_login, password: l_password})
        .then((data) => {
            console.log(data)
            if (data.status === '400' && data.key === 'USER') {
                error(data.message, 1)
                return
            }

            if (data.status === '400' && data.key === 'PASSWORD') {
                error(data.message, 2)
                return
            }

            if (data.status === '200') {
                mask.classList.add('hide')
                //setCookie('token', data.token, 2)
                location.replace(genURL('/'))

            }
        })
})