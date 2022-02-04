function genURL(url) {
    const ORIGIN = location.origin
    return ORIGIN + url
}


async function SendReceiptData(url = '', data) {
    mask.classList.remove('hide')
    document.querySelector('#success').innerText = 'Подождите....'
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: data
        });

        return await response.json()
    } catch (error) {
        document.querySelector('#success').innerText = error.message
        console.log('Ошибка: ' + error);
    }
}

var mask = document.querySelector('.mask')

const formData = new FormData();
const fileField_1 = document.querySelector('#image_1');

formData.append('image_1', '')

console.log('dwadwadsa')

fileField_1.addEventListener('change', (event) => {
    console.log('heeelow')
    if (fileField_1.files.length != 0) {
        document.querySelector('input[name="image_1"] + span').innerText = 'Добавлено фото'
        document.querySelector('.create_image').style.backgroundColor = '#6ec4c0'
    }  else {
        document.querySelector('input[name="image_1"] + span').innerText = 'Добавить фото'
    }
    formData.delete('image_1')
    formData.append('image_1', fileField_1.files[0], fileField_1.files[0].name)

})


const btn = document.querySelector('#SendData');


btn.addEventListener('click', (event) => {
    console.log('Geoloe');
    SendReceiptData(genURL('/create/receipt'), formData).then((data) => {
        console.log(data)
        if (data.status === '200') {
            mask.classList.remove('hide')
            document.querySelector('#success').innerText = data.message

            setTimeout(() => {
                location.replace(genURL('/'))
            }, 5000)
        }

        if (data.status === '400') {
            mask.classList.remove('hide')
            document.querySelector('#success').innerText = data.message

            setTimeout(() => {
                location.reload()
            }, 5000)
        }

    })
})
