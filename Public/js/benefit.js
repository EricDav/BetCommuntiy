if(!!window.IntersectionObserver){
    options = {
        rootMargin: "0% 0% 0% 0%",
        threshold: 1
    }
    let Observer = observer = new IntersectionObserver((entries, observer) =>{
        entries.forEach((entry)=>{
            if(entry.isIntersecting){
                $(entry.target).addClass('animate'); 
                // entry.unobserve();
            }
        });
    }, options);

    document.querySelectorAll('.item').forEach((box)=>{
        observer.observe(box);
    })

}
