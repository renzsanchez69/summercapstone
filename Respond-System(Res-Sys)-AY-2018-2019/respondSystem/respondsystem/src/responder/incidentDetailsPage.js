import React, {Component} from 'react';
import {Platform, 
	StyleSheet, 
	Text, 
	View, 
	AsyncStorage, 
	Image,
	TextInput,
	TouchableWithoutFeedback} 
	from 'react-native';
import {Icon}      from 'native-base';
import Geolocation from 'react-native-geolocation-service';
import ImagePicker from 'react-native-image-picker';
import Constants   from '../commons/Constants.js';
import Geocoder    from 'react-native-geocoding';


export default class IncidentDetailsPage extends Component{

	state = {

	}

	componentDidMount(){
		
	}	


	askButton = () =>{
		if(!this.props.doGetLoggedAccount.responding){
			  return <Text style={{
			  		color: Constants.USER_ROLES.NOT_CHOSEN_ROLE_COLOR
			  }}>
			  Ask For Assistance
			  </Text>
			}else{
			return <Text style={{
					color: '#000'
			}}>
			Ask For Assistance
			</Text>

		}
	}
	askAssistance = ()=>{
		if(!this.props.doGetLoggedAccount.responding){
			this.props.doDisplayAlertMessage('You must first respond and arrive in the incident');
			setTimeout(()=>{
				this.props.doDisplayAlertMessage('');
			},Constants.CONSOLE_TIME_DISPLAY);
		}
		else if(this.props.doGetLoggedAccount.responding.reportKey == String(this.props.doGetReportDetails.key) 
			&& this.props.doGetLoggedAccount.responding.status == String(Constants.RESPONDING_STATUS.ARRIVED)){
			this.props.doDisplayAlertMessage('Submitting, a moment..');
			this.props.FirebaseObject
				.database()
				.ref("Reports/"
					+String(this.props.doGetReportDetails.key))
				.update({
					'askHelp' : 'true'
				})
				.then(()=>{
					this.props.doDisplayAlertMessage('Success');
					setTimeout(()=>{
						this.props.doDisplayAlertMessage('');
					},Constants.CONSOLE_TIME_DISPLAY);
				})
				.catch((error)=>{
					this.props.doDisplayAlertMessage('An error has occured, try again');
					setTimeout(()=>{
						this.props.doDisplayAlertMessage('');
					},Constants.CONSOLE_TIME_DISPLAY);
				});
		}
		else{
			this.props.doDisplayAlertMessage('You must first respond and arrive in the incident');
			setTimeout(()=>{
				this.props.doDisplayAlertMessage('');
			},Constants.CONSOLE_TIME_DISPLAY);
		}
	}
	resolveButton = () =>{
		if(!this.props.doGetLoggedAccount.responding){
			  return <Text style={{
			  		color: Constants.USER_ROLES.NOT_CHOSEN_ROLE_COLOR
			  }}>
			  Resolve
			  </Text>
		}else{
			return <Text style={{
					color: '#000'
			}}>
			Resolve
			</Text>

		}
	}
	resolvePage = () =>{
		if (!this.props.doGetLoggedAccount.responding) {
			this.props.doDisplayAlertMessage('You cant resolve without responding');
			setTimeout(()=>{
				this.props.doDisplayAlertMessage('');
			},Constants.CONSOLE_TIME_DISPLAY);
		}else{
			this.props.doSetHomePage(Constants.RESPONDER_PAGE.RESOLVE_PAGE)
		}
	}

	respondButton = () =>{
		if(!this.props.doGetLoggedAccount.responding){
			  return <Text style={{
			  		color: '#000'
			 		 }}>
			  			Respond
			  		</Text>
		}else{
			return <Text style={{
						color: Constants.USER_ROLES.NOT_CHOSEN_ROLE_COLOR
					}}>
						Respond
					</Text>

		}
	}
	respondPage = () =>{
		if (this.props.doGetLoggedAccount.responding) {
			this.props.doDisplayAlertMessage('You are currently responding to the incident');
			setTimeout(()=>{
				this.props.doDisplayAlertMessage('');
			},Constants.CONSOLE_TIME_DISPLAY);
		}else{
			this.props.doSetHomePage(Constants.RESPONDER_PAGE.RESPONDING_LIST)
		}
	}


	
	render() {
	    return (
	    	<View style={{
	    			height: '100%',
	    			width: '100%',
	    			alignItems:'center'
	    	}}>
	    		<View style={{
	    			height: '9%',
	    			width: '100%',
	    			position: 'relative',
	    			backgroundColor: '#88ef92',
	    			top: '0%',
	    			flexDirection: 'row'
	    		}}>
	    			<TouchableWithoutFeedback
	    				onPress={()=>this.props.doSetHomePage(Constants.RESPONDER_PAGE.LIST_PAGE)}>
		    			<Text style={{
		    					height: '100%',
		    					width: '10%',
		    					fontSize: 15,
		    					fontWeight: 'bold',
		    					textAlign: 'center',
		    					position: 'relative',
		    					textAlignVertical: 'center',
		    					left: '10%'
		    			}}>	
		    				<Icon
		    					style={{
		    						fontSize:35,
		    						color: '#454647'
		    					}}
		    					name='ios-arrow-back'
		    					type='Ionicons'/>
		    			</Text>
		    		</TouchableWithoutFeedback>
	    		</View>
	    		<Text style={{
	    				height:'30%',
	    				width: '80%',
	    				left:'10%',
	    				position: 'absolute',
	    				top: '9%',
	    				fontSize: 16,
	    				fontWeight: 'bold',
	    				textAlignVertical: 'center',
	    				textAlign: 'center'
	    		}}>
	    			Loading...
	    		</Text>
	    		<Image
	         		source = {{uri:this.props.doGetReportDetails.imgURL}}
		         	style = {{width:'100%',position:'relative',height:'45%',resizeMode:'contain',marginBottom:'2%'}}/>
		        <Text style={{
		        		height: 45,
		        		width: '95%',
		        		paddingLeft:'2%',
		        		fontSize: 12.3,
		        		paddingLeft:'2%',
		        		color:'#000'
		        }}>
		        	Address: {this.props.doGetReportDetails.addressName}
		        </Text>
		        <Text style={{
		        		height:17.5,
		        		width: '95%',
		        		paddingLeft:'2%',
		        		fontSize: 12.3,
		        		paddingLeft:'2%',
		        		color:'#000'
		        }}>
		        	Time: {this.props.doGetReportDetails.timeReported}
		        </Text>
		        <Text style={{
		        		height:17.5,
		        		width: '95%',
		        		paddingLeft:'2%',
		        		fontSize: 12.3,
		        		paddingLeft:'2%',
		        		color:'#000'
		        }}>
		        	Incident: {this.props.doGetReportDetails.incidentType}
		        </Text>
				<Text style={{
						height: '8%',
						width: '95%',
						paddingLeft: '2%',
						fontWeight: 'bold',
						fontSize: 15,
						fontWeight: 'bold',
						color: '#000'
				}}>
					Details: {this.props.doGetReportDetails.reportInfo}
				</Text>
		        <View style={{
		        		height: '8.5%',
		        		width: '100%',
		        		justifyContent: 'space-evenly',
		        		position: 'relative',
		        		top: '15%',
		        		flexDirection: 'row'
		        }}>
		        	<TouchableWithoutFeedback
		        		onPress={()=>this.resolvePage()}>
			        	<Text style={{
			        			height: '100%',
			        			width: '26%',
			        			position: 'relative',
			        			borderRadius: 100,
			        			borderWidth: 2,
			        			textAlign:'center',
			        			textAlignVertical: 'center',
			        			fontSize: 15,
			        			fontWeight: 'bold',
			        	}}>
			        	{this.resolveButton()}
			        	</Text>
			        </TouchableWithoutFeedback>
			        <TouchableWithoutFeedback
		        		onPress={()=>this.props.doSetHomePage(Constants.RESPONDER_PAGE.RESPONDING_LIST)}>
			        	<Text style={{
			        			height: '100%',
			        			width: '26%',
			        			position: 'relative',
			        			borderRadius: 100,
			        			borderWidth: 2,
			        			textAlign:'center',
			        			textAlignVertical: 'center',
			        			fontSize: 15,
			        			fontWeight: 'bold',
	        					
			        	}}>
			        		{this.respondButton()}
			        	</Text>
			        </TouchableWithoutFeedback>
			        <TouchableWithoutFeedback
			        	onPress = {()=>this.askAssistance()}>
			        	<Text style={{
			        			width:'39%',
			        			textAlign:'center',
			        			textAlignVertical: 'center',
			        			height: '100%',
			        			position: 'relative',
			        			borderRadius: 100,
			        			borderWidth: 2,
			        			fontSize: 13.5,
			        			fontWeight: 'bold',
			        	}}>
			        		{this.askButton()}
			        	</Text>
			        </TouchableWithoutFeedback>
		        </View>
	    	</View>
	    );
	}
}