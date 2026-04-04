function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^|;\\s*)' + name + '=([^;]*)'))
    return match ? decodeURIComponent(match[2]) : null
}

async function request(method, url, body = null) {
    const options = {
        method,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-XSRF-TOKEN': getCookie('XSRF-TOKEN'),
        },
    }

    if (body) {
        options.body = JSON.stringify(body)
    }

    const response = await fetch(url, options)
    const data = response.status !== 204 ? await response.json() : null

    if (!response.ok) {
        throw { status: response.status, data }
    }

    return data
}

async function upload(url, formData) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-XSRF-TOKEN': getCookie('XSRF-TOKEN'),
        },
        body: formData,
    })

    const data = response.status !== 204 ? await response.json() : null

    if (!response.ok) {
        throw { status: response.status, data }
    }

    return data
}

export default {
    get: (url) => request('GET', url),
    post: (url, body) => request('POST', url, body),
    put: (url, body) => request('PUT', url, body),
    patch: (url, body) => request('PATCH', url, body),
    delete: (url, body) => request('DELETE', url, body),
    upload,
}
