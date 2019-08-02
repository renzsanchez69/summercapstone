import React, 
	{Component} 
	from 'react';
import {Platform, 
	StyleSheet, 
	Text, 
	View, 
	AsyncStorage,
	Image,
	NetInfo,
	TouchableWithoutFeedback,
	TextInput,
	Picker,
	CheckBox,
	FlatList,
	ScrollView} 
	from 'react-native';
import {
	Container, 
	Icon,
	Spinner} 
	from 'native-base';
/* -- Custom Components  -- */
import Constants from '../commons/Constants.js';

export default class OwnersLocation extends Component{

	state = {
		allFoodMenu     : [],
		finalFirebaseID : ''
	}

	componentDidMount(){
		const firebaseID = 	this.props.doUseFirebaseObject
								.database()
								.ref("RESTAURANT/"+String(this.props.doGetLoggedInformation.key)+"/Menu")
								.on("value",snapshot=>{
									if(snapshot.exists()){
										const allDishMenuWithKey = JSON.parse(JSON.stringify(snapshot.val()));
										const initAllFoodMenu    = [];
										Object
											.keys(allDishMenuWithKey)
											.forEach((dishKey)=>{
												initAllFoodMenu.push(allDishMenuWithKey[dishKey]);
											});
										this.setState({allFoodMenu:initAllFoodMenu});
										
									}
									else{
										this.setState({allFoodMenu:[]});
									}
								});
		this.setState({finalFirebaseID:firebaseID});
	}

	deleteADish = (item)=>{
		const data = {
			key : item.key
		};
		this.props.doDeleteADishMenu(data);
	}

	componentWillUnmount(){
		if(this.state.finalFirebaseID){
			this.props.doUseFirebaseObject
			.database()
			.ref("RESTAURANT/"+String(this.props.doGetLoggedInformation.key)+"/Menu")
			.off("value",this.state.finalFirebaseID);
		}
	}

	render(){
		return(
			<React.Fragment>
				<View style={{
	    				height:'100%',
	    				width:'100%',
	    				position:'relative',
	    				alignItems:'center'
	    		}}>
	    			<View style={{
	    					height: '8.5%',
	    					width:'100%',
	    					position:'relative',
						    borderColor: '#ddd',
						    borderBottomWidth: 0,
						    shadowColor: '#000',
						    shadowOffset: {
								width: 0,
								height: 5,
							},
							shadowOpacity: 0.34,
							shadowRadius: 6.27,
							elevation: 9,
						    backgroundColor: '#555dff',
						    flexDirection: 'row'
	    			}}>
	    				<TouchableWithoutFeedback
	    					onPress={()=>this.props.doSetHomePage(Constants.OWNER_ROLE_PAGES.RESTAURANT_INFO)}>
		    				<Text style={{
			    					height: '50%',
			    					width: '18%',
			    					position: 'relative',
			    					color: '#000',
			    					fontSize: 13,
			    					fontWeight: 'bold',
			    					textAlign: 'center',
			    					textAlignVertical: 'center',
			    					borderRadius: 100,
			    					borderWidth:2,
			    					left: '10%',
			    					top: '3%'
			    			}}>
			    				RETURN
			    			</Text>
			    		</TouchableWithoutFeedback>

	    				<Text style={{
	    						height:'100%',
	    						width:'45%',
	    						textAlign:'center',
	    						textAlignVertical:'center',
	    						fontSize:15,
	    						fontWeight:'bold',
	    						color:'#000',
	    						left: '60%'
	    				}}>
	    					Food Menu
	    				</Text>
	    			</View>

	    			<View style={{
	    				borderWidth: 1.2,
					    borderColor: '#ddd',
					    borderBottomWidth: 0,
					    shadowColor: '#000',
					    shadowOffset: {
							width: 0,
							height: 5,
						},
						shadowOpacity: 0.34,
						shadowRadius: 6.27,
						elevation: 10,
					    backgroundColor: '#fff',
	    				height: '74%',
	    				width:'90%',
	    				position: 'relative',
	    				elevation: 11,
						top: '5.5%',
					    alignItems: 'center',
					    paddingTop: '3%',
					    borderRadius: 25
	    			}}>
	    				<Text style = {{ 
	    						height:'10%',
	    						position: 'absolute',
	    						width: '80%',
	    						textAlignVertical:'center',
	    						textAlign :'center',
	    						fontSize: 14,
	    						top: '53%',
	    						color: '#000'
	    				}}>
	    					{(this.props.doGetLoggedInformation.Menu ? 
	    						( this.state.allFoodMenu.length == 0 ?
	    							'Getting your menu ready, please wait..' : '' ) : 'You haven\'t add any type of dish yet' )}
	    				</Text>
	    				<Text style ={{
	    						height: '15%',
	    						width: '50%',
	    						textAlignVertical: 'center',
	    						textAlign: 'center',
	    						position: 'relative',
	    						fontSize: 14,
	    						color: '#000'
	    				}}>
	    					<Icon
	    						style = {{fontSize:30}}
	    						name = 'ios-restaurant'
	    						type = 'Ionicons'/>
	    					{' Restaurant Menu'}
	    				</Text>
	    				<View style ={{
	    						width:'95%',
	    						height: '80%',
	    						position: 'relative'
	    				}}>
	    					<FlatList
				            	data= {this.state.allFoodMenu}
				            	renderItem={({item}) =>
				            		<View style = {{
				            			borderBottomWidth: 2,
				            			borderColor: '#000',
				            			height:95,
				            			width: '100%',
				            			position: 'relative',
				            			marginTop : 10,
				            			marginBottom :10,
				            			borderColor: '#979899'
				            		}}>
		            					<Text style ={{
			            						height: '19%',
			            						width: '100%',
			            						position: 'relative',
			            						fontSize: 13,
			            						color: '#000',
			            						textAlignVertical: 'center'
			            				}}>
			            					Name: {item.name}
			            				</Text>
			            				<Text style ={{
			            						height: '19%',
			            						width: '100%',
			            						position: 'relative',
			            						fontSize: 13,
			            						color: '#000',
			            						textAlignVertical: 'center'
			            				}}>
			            					Good for {item.persons}
			            				</Text>
			            				<Text style ={{
			            						height: '18%',
			            						width: '100%',
			            						position: 'relative',
			            						fontSize: 13,
			            						color: '#000',
			            						textAlignVertical: 'center'
			            				}}>
			            					Price in pesos: {item.price} 
			            				</Text>
			            				<Text style = {{
			            						height: '19%',
			            						width:'100%',
			            						position: 'relative',
			            						fontSize: 12,
			            						color: '#000',
			            						fontWeight: 'bold'
			            				}}>
			            					{(item.description.length == 0 ?
			            						'No description added': item.description)}
			            				</Text>
			            				<TouchableWithoutFeedback
			            					onPress = {()=>this.deleteADish(item)}>
				            				<Text style ={{
				            						height: '21%',
				            						width: '15%',
				            						position: 'relative',
				            						textAlignVertical: 'center',
				            						textAlign: 'center',
				            						left: '80%'
				            				}}>
				            					<Icon
				            						style ={{fontSize:18}}
				            						name = 'trash'
				            						type = 'FontAwesome'/>
				            				</Text>
				            			</TouchableWithoutFeedback>
			            			</View>
				            	}
				            	keyExtractor={item => item.key}/>
	    				</View>

	    				<Text 
			    			style={{
			    				height:'9%',
			    				width:'11%',
			    				position:'absolute',
			    				textAlign: 'center',
			    				textAlignVertical:'center',
			    				top: '96%',
			    				left: '3%'
			    		}}>
			    			<Icon
			    				style={{fontSize:30,color:'#000'}}
			    				name = 'ios-arrow-down'
			    				type = 'Ionicons'/>

			    		</Text>
	    			</View>
	    		</View>
			</React.Fragment>
		);
  	}
}