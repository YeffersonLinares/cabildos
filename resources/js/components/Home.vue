<template>
    <main class="main col-12">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><router-link :to="{ name: 'home'}"><span>Home</span></router-link></li>
        </ol>

        <div class="container-fluid">
            <!-- Tabla Listado -->
            <template>
                <div class="card">
                    <div class="card-header">
                        
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info" role="alert">
                            Componente Inicial!
                        </div>
                    </div>                   
                </div>
            </template>
        </div>
    </main>
</template>

<script>
    export default {
        data (){ 
            return {                 
                arrayRuta: [], 
            }
        },
        components:{},
        computed:{ },
        methods : {
        },
         mounted() { 
        }
    }
</script>
