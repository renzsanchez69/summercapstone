import React, 
	{Component} 
	from 'react';
import {Platform, 
	StyleSheet, 
	Text, 
	View, 
	Image,
	NetInfo,
	TouchableWithoutFeedback,
	TextInput,
	Picker,
	CheckBox,
	ScrollView} 
	from 'react-native';

	
/* -- Custom Components  -- */
import Constants from '../commons/Constants.js';



export default class OwnersLocation extends Component{

	state = {
		addressName : ''
	}

	getRestaurantAddress = ()=>{
		fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='+
			this.props.doGetUsersLocation.latitude +
			'&lon=' +
			this.props.doGetUsersLocation.longitude)
        .then(json => {
        	this.setState({
				addressName  : String(JSON.parse(json._bodyInit).display_name)
			});
        })
        .catch(error => console.log(error));
	}

	componentDidMount(){
		this.getRestaurantAddress();
	}

	submitLocation = ()=>{

		const data = {
			latitude    : this.props.doGetUsersLocation.latitude,
			longitude   : this.props.doGetUsersLocation.longitude,
			addressName : String(this.state.addressName)
		}

		if(this.props.doGetUsersLocation){
			if(this.state.addressName){
				this.props.doSetRestaurantAddress(data);
			}
			else{
				this.props.doSendAReportMessage('We are still getting your location, please wait..');
				setTimeout(()=>{
					this.props.doSendAReportMessage('');
				},Constants.REPORT_DISPLAY_TIME);
			}
		}
		else{
			this.props.doSendAReportMessage('We are still getting your location, please wait..');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
	}

	render() {
	    return (
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
	    						width:'50%',
	    						textAlign:'center',
	    						textAlignVertical:'center',
	    						fontSize:15,
	    						fontWeight:'bold',
	    						color:'#000',
	    						left: '53%'
	    				}}>
	    					Restaurant Location
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
		    				height: '60%',
		    				width:'90%',
		    				position: 'relative',
		    				elevation: 11,
							top: '15%',
						    alignItems: 'center',
						    paddingTop: '5%',
						    borderRadius: 25
	    			}}>
	    				<Text style = {{
	    						height: '55%',	
	    						width: '90%',
	    						position: 'relative',
	    						textAlign: 'center',
	    						textAlignVertical: 'center',
	    						fontSize: 14,
	    						color: '#000'
	    				}}>
	    					{'A warning to all restaurant owners: updating your establishment location can '
	  		  					+'only be done once. We suggest to click the button below and update such information whenever you are '
	  		  					+'precisely in your restaurant place. Hence, any mistake or concerns may be formally resolved or entertained '
	  		  					+'by filing a report in the feedback section of the application.'}
	    				</Text>
	    				<View style={{
	    						height: '18%',
	    						position: 'relative',
	    						width: '100%',
	    						flexDirection: 'row'
	    				}}>
	    					<Text style ={{
		    						height: '100%',
		    						position: 'relative',
		    						width: '20%',
		    						textAlign: 'center',
		    						textAlignVertical: 'center',
		    						fontSize: 13,
		    						fontWeight: 'bold',
		    						fontStyle: 'italic',
		    						color: '#000'
		    				}}>
		    					{'Current Location: '}
		    				</Text>
		    				<Text style = {{
		    					width: '80%',
		    					position: 'relative',
		    					height : '100%',
		    					textAlign: 'center',
	    						textAlignVertical: 'center',
	    						fontSize: 14,
	    						color: '#000'
		    				}}>
		    					{(this.state.addressName.length == 0 ?
		    						'Getting your address, please wait..' : this.state.addressName)}
		    				</Text>
	    				</View>
	    				<TouchableWithoutFeedback
	    					onPress={()=>this.submitLocation()}>
		    				<Text style={{
	    							height: '15%',
			    					width:'50%',
			    					position:'relative',
			    					borderWidth: 1.2,
								    borderColor: '#ddd',
								    borderBottomWidth: 0,
								    shadowColor: '#000',
								    shadowOffset: {
										width: 0,
										height: 2,
									},
									shadowOpacity: 0.34,
									elevation: 6,
								    backgroundColor: '#fff',
								    textAlignVertical: 'center',
								    textAlign: 'center',
								    fontWeight :'bold',
								    borderRadius : 15,
								    top: '6%',
								    color: '#000'
	    					}}>
	    						Set as your location
		    				</Text>
		    			</TouchableWithoutFeedback>
	    			</View>
	    		</View>
	    	</React.Fragment>
		);
  	}
}