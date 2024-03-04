//id интеграции
const client_id = '60db8fc0-5a0b-4b79-b7f4-749f98733d3b';
//Секрет интеграции
const client_secret = 'ZlXYRJBBQNFbNCKVaD1PhmnMf5GcwtwvnNFWUg8w4Roj6zB3jIVe13jNc8nIj642';

const redirect_uri = 'https://webhook.site/61a0b739-4575-4b86-be79-1b5e5e743fec';

//Код авторизации
const authorization_code = 'def50200f5a80038a2ec4e82ffdbf837755a17cf696869f6070f8302bcb5172e60a6972fe0a39e0add8a1e9a144b9927c43015f164bc73fd107862ac1630cf8c3de94a695fd97556c2268f73c4753ff65193ed3b154c593fd41764a0b7038dd3cfabba5099c5b92b41536e4f83afbdd50d31f092516d6dc84a2ded6353708bb51b1eefd139cea28cf5768c272e79562b44a749e1c4d02ba3ca68d1a2da2d0b763edb75de6f843eec407e1cdbdf22e345c77e81d3725c9c71473fde60b1aa024941da47806c31dab569faef05f34c252413b99f658e69a6960794fe41bdf04af43af0535d4f888630d48a8a6f27226e08a53597699e5c46f50ae548618dab3856c811bcf42154a52c602298506bf9482cca90c8b686214196b2faec5910c7ca21584991a5bae5daed452ed8d657283210365505eb6f377b780b78946870cbe51dc6d99bc7cbf45890387a59ef3917ab3b2690d1deb4aa4b93610ac6c2b53e76c8bb4a367faeec46ec42e6db82f46259ac020ae5a88e47f88b7f0d5f3d2a5ce0903904009b990a0aba03d2c03e31ee958468e0c18067774e35dbf67c1d9f78e49c6f4b82ae6e38d180cdfbf9e8f328f0eed174998373ce5496c6ec31515c64321a461b272d836241ff02af0b38cb5dd6d22db87dbf7b33e4ad351bfd974b4f8659710ac9357a4cb0566b5586d742734253acdca08c7da200b85cb442266be72a85cd09a87bf705364c1f5cdd';

let response = await fetch('https://ustigyue.amocrm.ru/oauth2/access_token', {
    method: 'POST',
    headers: {
        'Content-type': 'application/json; charset=UTF-8'
    },
    body: JSON.stringify({
        'client_id': client_id,
        'client_secret': client_secret,
        'grant_type': 'authorization_code',
        'code': authorization_code,
        'redirect_uri': redirect_uri
    })
});

let response_body = await response.json();
console.log(response_body);