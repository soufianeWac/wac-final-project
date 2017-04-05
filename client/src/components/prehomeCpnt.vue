<template>
	<div>
		<h2>Vidincloud</h2>
	</div>
</template>

<script>
	export default{
		name: 'prehomeCpnt',
		data: function(){
			return {
				msg: 'salut', 
				users: [],
			}
		}
		methods: {
			getUsers: function(){
				$.posrt data
					this.users = data;
			}
		}
	}
</script>