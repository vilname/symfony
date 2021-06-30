jQuery(document).ready(function() {
    // $.ajax({
    //     method: "POST",
    //     url: "http://localhost:7777/api-platform/graphql",
    //     contentType: "application/json",
    //     headers: {
    //         Connection: "keep-alive",
    //         Accept: "application/json",
    //         "X-KL-Ajax-Request": "Ajax_Request",
    //         "sec-ch-ua-mobile": "?0",
    //         "Content-Type": "application/json",
    //         "Origin": "http://localhost:7777",
    //         "Sec-Fetch-Site": "same-origin",
    //         "Sec-Fetch-Mode": "cors",
    //         "Sec-Fetch-Dest": "empty"
    //     },
    //     data: {
    //         JSON.stringify(
    //             query:
    //         )
    //     },
    //     success: function (data) {
    //         console.log('data', data)
    //     }
    // })

    $('.find-user-group #form_users').on('change', (e) => {
        console.log('e', e.currentTarget.value)

        $.ajax({
            method: 'POST',
            url: 'http://localhost:7777/api/v1/user/search-user-group',
            data:{
                id: e.currentTarget.value
            },
            success: (res) => {
                var best_group = '';
                for (key in res.best_groups) {
                    var item = res.best_groups[key]
                    best_group += `<div>
                        <span>Название группы - ${item.group_name}</span>
                        <span>Количество совпавших навыков - ${item.cnt}</span>
                        </div>`
                }

                $('.find-user-group--free-group').empty()
                $('.find-user-group--best-group').empty()

                if (res.best_free_groups.message) {
                    $('.find-user-group--free-group').html(res.best_free_groups.message);
                } else {
                    $('.find-user-group--free-group').html(`<span>Ближайшая подходящая группа со свободными местами - ${res.best_free_groups.group_name}</span>`);
                }

                $('.find-user-group--best-group').html(best_group)



            }
        })
    })
})

