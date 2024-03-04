const refreshToken = 'def502007f953f71a221d4d2aaae408193726988025a6c59327c4c8d95ffb0705e0b14527bc69eac8866ea97207522ced57921149a179e0616e676106effbd2213c637e6eb32b9bbee0c5e51fc218c750710eec25f2a022f2ccd662baf45cfcb49944e41daf2ad992e28468548c62a154f382e379618c76f7681e3bc5e1b4e060ed4c9406f0b3600ef18ddb46aae5c5ffc40c2fefa41a8f96de99f3f5f25570abcb09b5384e2286bcc451712fa88047eeaed323dd0e0fc32a72139be837d584c664a746ce5407ecb90e9e0b43773ec9582c8cac5dfdb92312e084b8621f04d79c503c083706690047470e6e637f2e092d5f3771049ec031cf6440391a4e37ddcf285380f0c1d65ddb66bba7c6beb38a59fa0917a5a87902b433867c86048b15f3b7797cf00a22903b8063cce88422201d6e24758032951baddc51f08646033bcb1d0a26cded6916c27d1d402617661be552be3f51f7a8af9a55bbcb80dfc8c7bd7e79588bfebf7c9f06528ffcdffbc5dc3f66be61e24b8027aba63e93e669499f410aefabb4a81c5520456975184b826eec2accd365c5e64693122125036ec62c27b11b494eec848d7aede5194995297e46ec295880f10e1d52b64c9adcc662880fa9bf76345a2ad9cef1b04aadd58f967fe31c1b8f9672be3c4e08d33acfbecedc70a83d72f622bb35edf346ee8';
const accessToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImEzNmY5Mjk1MWYwOGM3NjIzYzA3OWVmNWYxYjI2NGYxZTZlODZkMzM1YWQzZGFlZGRlNDZlZDM3OTgwYWE4NGIyM2M5YWMzY2VkNDFiNGJmIn0.eyJhdWQiOiI2MGRiOGZjMC01YTBiLTRiNzktYjdmNC03NDlmOTg3MzNkM2IiLCJqdGkiOiJhMzZmOTI5NTFmMDhjNzYyM2MwNzllZjVmMWIyNjRmMWU2ZTg2ZDMzNWFkM2RhZWRkZTQ2ZWQzNzk4MGFhODRiMjNjOWFjM2NlZDQxYjRiZiIsImlhdCI6MTcwOTE5NjUyMSwibmJmIjoxNzA5MTk2NTIxLCJleHAiOjE3MDkyODI5MjEsInN1YiI6IjEwNzI0NDUwIiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjMxNTk0NzEwLCJiYXNlX2RvbWFpbiI6ImFtb2NybS5ydSIsInZlcnNpb24iOjIsInNjb3BlcyI6WyJwdXNoX25vdGlmaWNhdGlvbnMiLCJmaWxlcyIsImNybSIsImZpbGVzX2RlbGV0ZSIsIm5vdGlmaWNhdGlvbnMiXSwiaGFzaF91dWlkIjoiNWJiMTMzMjctYzUyOS00MzhjLWIzNzQtYWI2YWJjNDZkZjhhIn0.hIprRlsZIgj67lbZHJzXkVeiDMCpJT0fWRt3SWp7FRHRRu3CuEAyrFGRtmpWIxdl2EBoOVyhT-HHZEKxz8wjh8h5SbTalNhviIi8Z8QXy9UvpGfAMrAaoWspK7JU4l9EcnyZ5BMmRuV1y-HdldeLMTnLd8K24t2AxzUxVEtlvo0jypcX4x2FEV8jzEVSlj3L0y4kR47Lsb_WHjft0yf8qOk6kO0qom1HATYneAoYUE6gCX7_QoEDuvDN03Cuafnz4u63uxZ7m22W2ZDDH7Imtx9hOCR2vC7rlwum4Ph9U6HoWeg1mBcqdo4GTc9RfgJ2d9UBqRdI6RBgJ9bNfXa_Cg';

const baseUrl = 'https://corsproxy.io/?' + 'https://ustigyue.amocrm.ru';

main();


async function main() {
    let currentPage = 1;
    let currentQuantityLeads = 2;
    let currentOrder = 'title_asc';

    //Кнопки пагинации
    const paginationBtnPrev = document.querySelector('.pagination_btn_prev');
    const paginationBtnNext = document.querySelector('.pagination_btn_next');
    
    //Вызов со значениями по умолчанию
    updateView(currentPage, currentQuantityLeads, currentOrder, paginationBtnPrev, paginationBtnNext);

    //Обработчик клика по кнопкам пагинации
    paginationBtnPrev.addEventListener('click', () => {
        currentPage--;

        updateView(currentPage, currentQuantityLeads, currentOrder, paginationBtnPrev, paginationBtnNext);
    });
    paginationBtnNext.addEventListener('click', () => {
        currentPage++;

        updateView(currentPage, currentQuantityLeads, currentOrder, paginationBtnPrev, paginationBtnNext);
    });

    // Обработчик радиокнопки переключения кол-ва сделок на странице
    const controllerQuantityLeads = document.querySelector('.controllerQuantityLeads');
    controllerQuantityLeads.addEventListener('change', (event) => {
        currentPage = 1;
        currentQuantityLeads = event.target.value;

        if(currentQuantityLeads === 'all') {
            hidePaginationBtns(paginationBtnPrev, paginationBtnNext);

            const limitLeads = 5;
            let page = 1;

            renderAll();

            async function renderAll() {
                while(await updateView(page, limitLeads, currentOrder, '', '')) {
                    if(page !== 1) {
                        sleep(2000);
                    }
                    page++;    
                }

                //Сортировка всех сделок на странице
                rerenderAllRowsWithOrder(currentOrder);
            }

        } else {
            updateView(currentPage, currentQuantityLeads, currentOrder, paginationBtnPrev, paginationBtnNext);
        }

    });

    //Обработчик сортировки
    const controllerOrder = document.querySelector('.controllerOrder');
    controllerOrder.addEventListener('change', (event) => {
        currentOrder = event.target.value;

        updateView(currentPage, currentQuantityLeads, currentOrder, paginationBtnPrev, paginationBtnNext);
    })

}

function sleep(milliseconds) {
    const date = Date.now();
    let currentDate = null;
    do {
      currentDate = Date.now();
    } while (currentDate - date < milliseconds);
}

function hidePaginationBtns(paginationBtnPrev, paginationBtnNext) {
    paginationBtnPrev.classList.add('pagination_btn_inactive');
    paginationBtnNext.classList.add('pagination_btn_inactive');
}

async function updateView(currentPage, currentQuantityLeads, currentOrder, paginationBtnPrev, paginationBtnNext) {
    let leads = await getLeads(currentPage, currentQuantityLeads);

    if(leads) {
        
        if(paginationBtnPrev && paginationBtnNext) {
            renderRows(leads, currentOrder);
            setStatusPaginationBthPrev(paginationBtnPrev, currentPage, currentQuantityLeads);
            setStatusPaginationBthNext(paginationBtnNext, currentPage, currentQuantityLeads);
        } else {
            //Рендер всех сделок
            if(currentPage == 1) {
                //Очищаем таблицу только для первого рендера
                renderRows(leads, currentOrder);
            } else {
                renderRows(leads, currentOrder, false);
            }

        }

        return true;
    } else {
        return false;
    }

}

async function setStatusPaginationBthPrev(paginationBtnPrev, currentPage, currentQuantityLeads) {
    if(currentPage - 1 === 0) {
        paginationBtnPrev.classList.add('pagination_btn_inactive');
        return;
    }

    if(await getLeads(currentPage - 1, currentQuantityLeads)) {
        paginationBtnPrev.classList.remove('pagination_btn_inactive');
    } else {
        paginationBtnPrev.classList.add('pagination_btn_inactive');
    }
}

async function setStatusPaginationBthNext(paginationBtnNext, currentPage, currentQuantityLeads) {
    if(await getLeads(currentPage + 1, currentQuantityLeads)) {
        paginationBtnNext.classList.remove('pagination_btn_inactive');
    } else {
        paginationBtnNext.classList.add('pagination_btn_inactive');
    }
}


async function getLeads(page = 1, limit = 2) {
    try {
        const response = await fetch(`${baseUrl}/api/v4/leads?page=${page}&limit=${limit}`, {
            headers: {
                'Authorization': `Bearer ${accessToken}`
            },
        });
    
        if(response.ok) {
            const leadsBody = await response.json();
            const leads = leadsBody['_embedded']['leads'];
    
            // console.log(leads);
            return leads;
        } else {
            throw new Error('Ошибка' + response.status);
        }
    } catch(error) {
        console.log(error.message);
        return false;
    }


}

function sortLeadsByType(leads, typeOrder) {
    switch (typeOrder) {
        case 'title_asc':
            leads.sort((a, b) => a.name.localeCompare(b.name));
            break;

        case 'title_desc':
            leads.sort((a, b) => b.name.localeCompare(a.name));
            break;

        case 'price_asc':
            leads.sort((a, b) => a.price - b.price);
            break;

        case 'price_desc':
            leads.sort((a, b) => b.price - a.price);
            break;
    }
    return leads;
}

function rerenderAllRowsWithOrder(typeOrder) {
    const tbodyElement = document.querySelector('.tbodyElement');
    const tableRows = tbodyElement.querySelectorAll('tr');

    const rowsArray = Array.from(tableRows);

    if(typeOrder === 'price_desc') {
        rowsArray.sort((a, b) => {
            const priceA = parseFloat(a.querySelector('td:nth-child(2)').innerText);
            const priceB = parseFloat(b.querySelector('td:nth-child(2)').innerText);
    
            return priceB - priceA;
        });
    } else if(typeOrder === 'price_asc') {
        rowsArray.sort((a, b) => {
            const priceA = parseFloat(a.querySelector('td:nth-child(2)').innerText);
            const priceB = parseFloat(b.querySelector('td:nth-child(2)').innerText);
    
            return priceA - priceB;
        });
    } else if(typeOrder === 'title_desc') {
        rowsArray.sort((a, b) => {
            const nameA = a.querySelector('td:nth-child(1)').innerText.trim().toUpperCase();
            const nameB = b.querySelector('td:nth-child(1)').innerText.trim().toUpperCase();
        
            return nameB.localeCompare(nameA);
        });
    } else if(typeOrder === 'title_asc') {
        rowsArray.sort((a, b) => {
            const nameA = a.querySelector('td:nth-child(1)').innerText.trim().toUpperCase();
            const nameB = b.querySelector('td:nth-child(1)').innerText.trim().toUpperCase();
        
            return nameA.localeCompare(nameB);
        });
    }

    tbodyElement.innerHTML = '';

    rowsArray.forEach(row => {
        tbodyElement.appendChild(row);
    });
}


function renderRows(leads, typeOrder, refreshRows = true) {
    const tbodyElement = document.querySelector('.tbodyElement');

    if(refreshRows) {
        tbodyElement.innerHTML = '';
    }

    leads = sortLeadsByType(leads, typeOrder);
    leads.forEach(lead => {
        const rowTable = `
            <tr>
                <td>
                    ${lead.name}
                </td>
                <td>
                    ${lead.price}
                </td>
                <td>
                    ${formatTimestamp(lead.created_at)}
                </td>
                <td>
                    ${lead.responsible_user_id}
                </td>
            </tr>
        `;

        tbodyElement.innerHTML += rowTable;
    });
}

function formatTimestamp(timestamp) {
    const date = new Date(timestamp * 1000);
    const formattedDate = date.toLocaleString('ru-RU', {timeZone: 'Europe/Moscow'});

    return formattedDate;
}