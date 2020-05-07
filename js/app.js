let container = document.querySelector('.container')
let addContact = document.querySelector('.add-contact')


addContact.addEventListener('click', function(){
    createElement(container)

})


function createElement(parent){
    let contact = document.querySelector('.contact')
    let clonContact = contact.cloneNode(true)
    
    addItem(clonContact)

    deleteItem(clonContact)

    changeItem(clonContact)

    parent.appendChild(clonContact)

}

function addItem(elem){
    whichDisplay(elem,'d-none','d-grid')  
}

function deleteItem(elem){
    let del = elem.querySelector('.contact-buttons_delete')
    del.addEventListener('click', function(){
        let thisContact = this.closest('.contact')
        thisContact.remove()
    })
}

function changeItem(elem){
    let change = elem.querySelector('.contact-buttons_change')
    let cells = elem.querySelectorAll('.add-info')
    let counter = 0 


    
    change.addEventListener('click', function(){
        counter++
        if(counter % 2){
            for(let i = 0;i < cells.length;i++){  
                if(cells[0].value.length > 0 && cells[1].value.length > 0 && cells[2].value.length > 0 && cells[2].value.length < 12){                
                    cells[i].previousElementSibling.innerText = cells[i].value
                    whichDisplay(cells[i].previousElementSibling,'d-none','d-block')
                    whichDisplay(cells[i],'d-block','d-none')   
                    this.innerText = 'Изменить'         
                }
                else{
                    counter = counter-1
                    if(cells[i].value.length < 1){
                        cells[i].style.border = '1px solid red'                       
                    }else if(cells[2].value.length > 12){
                        cells[2].style.border = '1px solid red'     
                    }else{
                        cells[i].style.border = '1px solid #000000'                 
                    }            
                }                     
            }
        }else{
            for(let i = 0;i < cells.length;i++){  
                whichDisplay(cells[i].previousElementSibling,'d-block','d-none')
                whichDisplay(cells[i],'d-none','d-block')  
                this.innerText = 'Сохранить'    
            }
        }
    })

}



//первый параметр элемент который будет меняться, второй класс которое будет удаляться,
//третий класс которое будет добавляться
function whichDisplay(elem,del,add){
    elem.classList.remove(del)
    elem.classList.add(add)
}

