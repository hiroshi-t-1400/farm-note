// /var/www/src/resources/js/components/modules/admin/requests/users/error.js

function handleRequestError(error) {
    console.log({ 'error': error });
    if (error.type === 'validation') {
        this.errors = error.errors;
        alert(error.message);
        return;
    }
    alert(error.message);
}

export default handleRequestError;
