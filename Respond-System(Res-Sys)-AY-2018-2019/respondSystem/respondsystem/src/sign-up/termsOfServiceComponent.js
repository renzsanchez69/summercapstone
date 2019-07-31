import React, {Component} from 'react';
import {Platform, 
	StyleSheet, 
	Text, 
	View, 
	AsyncStorage, 
	Image,
	ScrollView,
	TextInput,
	TouchableWithoutFeedback} 
	from 'react-native';
import { Container, 
	Icon} 
	from 'native-base';


/* -- Custom made components -- */
import Constants      from '../commons/Constants.js';

export default class TermsOfService extends Component {

	getBackToSingupP2 = ()=>{
		this.props.doSetTemplateDisplay(Constants.PAGES.SIGN_UP_PAGE_2);
	}

	render(){
		return(
			<React.Fragment>
				<Image source={require('../img/background.png')}
				    		style={{height: '100%',
				    				width:'100%',
				    				resizeMode:'stretch',
				    				position:'absolute',
				    				}}/>
		    	<View style={{
		    		width:'100%',
		    		height:'100%',
		    		alignItems: 'center'
		    	}}>
		    		<View style={{
		    				height: '7%',
		    				width: '100%',
		    				flexDirection: 'row',
		    				position:'relative',
		    				top: '2%',
		    				justifyContent:'flex-start',
		    				alignItems:'center'
		    		}}>
			    		<TouchableWithoutFeedback
		    				onPress={()=>this.getBackToSingupP2()}>
			    			<Text style={{
			    					height:'100%',
			    					position: 'relative',
			    					width: '10%',
			    					left: '18%'
			    			}}>
			    				<Icon
			    					style={{
			    						fontSize:40,
			    						color: '#454647'
			    					}}
			    					name='ios-arrow-back'
			    					type='Ionicons'/>
			    			</Text>
			    		</TouchableWithoutFeedback>
			    		<Text style={{
		    					height: '100%',
		    					width: '50%',
		    					fontSize: 18,
		    					paddingTop: '2.5%',
		    					fontWeight: 'bold',
		    					textAlign:'center',
		    					textAlignVertical:'center',
		    					position: 'relative',
		    					left: '130%'
		    			}}>
		    				Terms of Service
		    			</Text>
			    	</View>
			    	<View style={{
		    				height: '77%',
		    				width: '100%',
		    				top:'8%',
		    				alignItems: 'center'
		    		}}>
		    			<ScrollView 
		    				style ={{
		    					width: '100%',
		    					position: 'relative'
		    				}}
		    			>
			    			<Text 
			    				style = {{
			    					width: '100%',
			    					fontSize: 14,
			    					padding: '5%',
			    					color: '#000'
			    				}}>
				    			{
				    				'PRIVACY POLICY:\n\n'
				    				+'Prince Claire Joshua C. Caino, Michael Balagtas, Kenneth Alfanta & Syd Suarez built the Res-Sys Application app as a Free app. This SERVICE is provided by Prince Claire Joshua C. Caino, Michael Balagtas, Kenneth Alfanta & Syd Suarez at no cost and is intended for use as is.'
				    				+'\n\n'
				    				+'This page is used to inform visitors regarding my policies with the collection, use, and disclosure of Personal Information if anyone decided to use my Service.'
				    				+'\n\n'
				    				+'If you choose to use my Service, then you agree to the collection and use of information in relation to this policy. The Personal Information that I collect is used for providing and improving the Service. I will not use or share your information with anyone except as described in this Privacy Policy.'
				    				+'\n\n'
				    				+'The terms used in this Privacy Policy have the same meanings as in our Terms and Conditions, which is accessible at Res-Sys Application unless otherwise defined in this Privacy Policy.'
				    				+'\n\n'
				    				+'INFORMATION COLLECTION  AND USE:\n\n'
				    				+'For a better experience, while using our Service, I may require you to provide us with certain personally identifiable information. The information that I request will be retained on your device and is not collected by me in any way.'
				    				+'\n\n'
				    				+'The app does use third party services that may collect information used to identify you.'
				    				+'\n\n'
				    				+'Link to privacy policy of third party service providers used by the app'
				    				+'\n\n'
				    				+'-Google Play Services'
				    				+'\n\n'
				    				+'SECURITY:'
				    				+'\n\n'
				    				+'I value your trust in providing us your Personal Information, thus we are striving to use commercially acceptable means of protecting it. But remember that no method of transmission over the internet, or method of electronic storage is 100% secure and reliable, and I cannot guarantee its absolute security.'
				    				+'\n\n'
				    				+'TERMS AND CONDITIONS:'
				    				+'\n\n'
				    				+'You are hereby accept our terms and condition to not give false or fabricated information to mislead or prevent the law enforcement agencies from protecting the life or property of the victim; or publishing or fabricating information from the data gathered in confidence by investigating authorities.'
				    				+'\n\n'
				    				+'Criminal Code of the Philippines'
				    				+'\nUnder Chapter 2 Crimes Affecting State Function '
				    				+'\nSection: 37. Obstruction of Justice:'
				    				+'\n\n'
				    				+'Any person who willfully obstructs, impedes,frustrates or delays the apprehension of suspects or the investigation or prosecution of criminal cases, or intrudes in a crime scene shall be punished within Level 3. '
				    				+'\n\n'
				    				+'*Penalty for offenders*'
				    				+'\n\n'
				    				+'1. First offense - Warning from the administration via text message or email from the users information.'
				    				+'\n2. Second offense - Warning from the administration via text message or email from the users information for the last offense.'
				    				+'\n3. Third offense - the penalty shall be arresto menor or a fine not exceeding 200 pesos and termination of account.'
				    				+'\n\n\n'
				    				+'CHANGES TO THIS PRIVACY POLICY:'
				    				+'\n\n'
				    				+'I may update our Privacy Policy from time to time. Thus, you are advised to review this page periodically for any changes. I will notify you of any changes by posting the new Privacy Policy on this page. These changes are effective immediately after they are posted on this page.'
				    				+'\n\n'
				    				+'CONTACT US:'
				    				+'\n\n'
				    				+'If you have any questions or suggestions about my Privacy Policy, do not hesitate to contact me at resysrespond@gmail.com. '
				    			}
			    			</Text>
			    		</ScrollView>
		    		</View>
		    	</View>
			</React.Fragment>
		);
    }
}