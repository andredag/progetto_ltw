var tab_esame = new Vue({
    el: '#table_body',
    data: {
      esami: "ciao"
      
    },
    methods: {
      allRecords: function(){
  
        axios.get('getEsami.php')
        .then(function (response) {
           tab_esame.esami = response.data;
        })
        .catch(function (error) {
           console.log(error);
        });
      }
    },
    created: function(){
        this.allRecords()
    }
})
