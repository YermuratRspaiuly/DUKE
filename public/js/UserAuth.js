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


function error(errorText,id){
    document.querySelector(`#reg_${id}`).innerText = errorText
    setTimeout(()=>{
        document.querySelector(`#reg_${id}`).innerText = ''
    }, 5000)
}


var mask = document.querySelector('.mask')
let reg_login = document.querySelector('.registration input[name="login"]');
let reg_password = document.querySelector('.registration input[name="password"]');
let reg_p_repeat = document.querySelector('.registration input[name="p_repeat"]');
var login = ''
var password = ''
var p_repeat = ''


reg_login.addEventListener('change', (event) => {
    login = event.target.value
})

reg_password.addEventListener('change', (event) => {
    password = event.target.value
})

reg_p_repeat.addEventListener('change', (event) => {
    p_repeat = event.target.value
})

let sendRegistration = document.querySelector('.registration input[type="submit"]')

sendRegistration.addEventListener('click', () => {
    if (!(login.length >= 2)) {
        error('Логин не может быть меньше 2 символов', '1')
        return
    }

    if (!(password.length >= 4)) {
        error('Пароль не может быть меньше 4 символов', '2')
        return
    }

    if (!(p_repeat.length >= 4)) {
        error('Пароль не может быть меньше 4 символов', '3')
        return
    }
    SendUserData(genURL('/signup'), { login: login, password: password, p_repeat: p_repeat})
        .then((data) => {
            if (data.status === '400' && data.key === 'PASSWORD') {
                error(data.message, '2')
                error(data.message, '3')
                mask.classList.add('hide')
            }

            if (data.status === '400' && data.key === 'USER') {
                error(data.message, '1')
                mask.classList.add('hide')
            }

            if (data.status === '200') {
                mask.classList.remove('hide')
                document.querySelector('#success').innerText = data.message

                setTimeout(()=>{
                    location.replace(genURL('/signin'))
                }, 5000)
            }
        })
})

