document.getElementById("urlForm").addEventListener("submit", function(event) {
    event.preventDefault();
    const urlInput = document.getElementById("urlInput").value;

    fetch("index.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "url=" + encodeURIComponent(urlInput)
    })
    .then(response => response.json())
    .then(data => {
        if (data.short_url) {
            document.getElementById("result").innerHTML = 
            `<div class='shortened-url'>
                Short URL: <a href='${data.short_url}' target='_blank'>${data.short_url}</a>
                <button onclick="copyToClipboard('${data.short_url}')" class="btn btn-primary">Copy</button>
            </div>`;
        } else if (data.error) {
            document.getElementById("result").innerHTML = `<div class='error'>${data.error}</div>`;
        }
    })
    .catch(error => {
        document.getElementById("result").innerHTML = `<div class='error'>An error occurred. Please try again.</div>`;
    });
});

function copyToClipboard(text) {
    const elem = document.createElement('textarea');
    elem.value = text;
    document.body.appendChild(elem);
    elem.select();
    document.execCommand('copy');
    document.body.removeChild(elem);
    alert('Copied to clipboard');
}
