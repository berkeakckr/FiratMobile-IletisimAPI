import {
    ScrollView,
    View,
    FlatList,
    Text,
    TextInput,
    Image,
    Alert,
    Dimensions,
    RefreshControl,
} from 'react-native';
import React, { useState, useEffect } from 'react';
import styles from './styles';
import ChatList from '../../Components/chatList';
import { networkPaths, axiosApiInstance as axios } from '@service';
import { TouchableOpacity } from 'react-native-gesture-handler';
import Header from '../../Components/header';
import socketio, { io } from 'socket.io-client';
import AsyncStorage from '@react-native-async-storage/async-storage';
import ROUTES from '../../Configs/routes';

export default function Message({ navigation, route }) {
    const windowWidth = Dimensions.get('window').width;
    const windowHeight = Dimensions.get('window').height;
    const [message, setMessage] = useState('');
    const [messageData, setMessageData] = useState([]);
    const [messageDataController, setMessageDataController] = useState([]);
    const [activity, setActivity] = useState(true);
    const [refreshing, setRefreshing] = useState(false);
    const [dataIsFinished, setDataIsFinished] = useState(false);
    let refresingForAxios = false;
    const [data, setData] = useState(0);
    // const accessToken =
    //   'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiOWYxNTE1Y2RjMjA0NDQ4M2Y1NGE0MjY2NTk5NTc2YzFmOGNhZDg1NGNhMWU0YzQyNDM5ZDNiMGU4N2UyNGE0M2I4MTBmYTc5YmZiNWJlNzUiLCJpYXQiOjE2NzQzODgyNzEuNDAwMjIyLCJuYmYiOjE2NzQzODgyNzEuNDAwMjI0LCJleHAiOjE3MDU5MjQyNzEuMzkzOTE5LCJzdWIiOiI3Iiwic2NvcGVzIjpbXX0.jzey5EeJn3fu6hQHsqnngcMDb1bgMUisiaM4mgv3q76j742dmytsyWELU642ISQc43kdffnkyR6ple4-1yfSoAPF4ljcqdi0xMAMKwj-hGug6ZzEwfaSD94Nhc9FgtGn_tGxUjPJyrwA1XYAOfC5JeL3grODZxn1ylQ_6B8v55KHfs7Tw-Y8FAvH-9BUvG3BdOMzJd1sCPII6JBrzDspYNXeKCdmWp26EkNqD5LjpoNA6CMsCreJrrwAJWynv3CEFL9y1x-8lmzQUb2gw_rqUkCkg2gw04pDdFWmquSPbBf27omgfgCaxCT96Y-spR67CSO6KFfVKMzJCaATCUG1NErhbKnbdSzFiYnyN_4564sTXE8I3jWA59MxaD17f98gPLEMCt6z_scxBCrityy7mxMue8KGNbpuHQqOaZlBcKnmRU74Hd8h3zUezrEcks6B2eUYELBCZDgmbDWovwv8ftm9voI6FozSXOU27lnuEGUeKxnahhuFVQGKkolB1G-pRmWSBNA5jqtBF_zTlDRyBYO9tbSQfEYfT3Jti51OQlUr0Fn5Tgg9MYKTP9nCU7EmRUzQ1WJsnLcAnJUTqigDE-vIAbOhlPszOoC9txD3YpaQ_vXG4layOYZl1K77IsI0UtFi3fRPSmAq3eFBpJjlftBLVnZPNMLiT5g5b6ZqyU0';
    // console.log('access_token', accessToken);

    const id = route.params.id;
    const message_id = route.params.message_id;
    const chat_name = route.params.chat_name;
    // const socket = socketio.connect(
    //   networkPaths.BASE_URL + networkPaths.GROUP_CHAT + id,
    //   {
    //     auth: 'Bearer ' + accessToken,
    //   },

    // );
    // socket.on('connect', function () {
    //   console.log('Bağlandı');
    //   console.log('socket.connected', socket.connected);
    //   setData[data];
    // });
    // console.log('socket', socket);

    const Messages = async() => {
        axios
            .get(networkPaths.GROUP_CHAT + message_id)
            .then(async function(responseJson) {
                setMessageDataController(responseJson.data.messages);
                setRefreshing(false);
            })
            .catch(error => {
                console.error(error, 'ERROR');
            });
    };
    // const timer = setTimeout(() => {
    //   setData(data + 1);
    //   Message();
    // }, 3000);
    useEffect(() => {
        Messages();
        // const interval = setInterval(() => {
        //   console.log('This will run every second!');
        // }, 1000);
        // return () => clearInterval(interval);
    }, []);

    const SendMessage = () => {
        if (message === '') {
            Alert.alert('Uyarı!', 'Mesaj boş göderilemez', [{
                text: 'Tamam',
                onPress: () => {},
            }, ]);
        } else {
            axios
                .post(networkPaths.SEND_CHAT + message_id, { text: message })
                .then(async function(responseJson) {
                    setMessage('');
                    Messages();
                })
                .catch(error => {
                    console.error(error, 'ERROR');
                });
        }
    };

    const renderItem = item => {
        return ( <
            ChatList text = { item.text }
            user_id = { item.id }
            user_name = { item.name }
            />
        );
    };
    const onRefresh = () => {
        setRefreshing(true);
        setDataIsFinished(false);
        refresingForAxios = true;
        Messages();
    };

    return ( <
            View style = { styles.Container } >
            <
            Header head_title = { chat_name }
            onpress = {
                () => navigation.goBack()
            }
            /> {
            activity ? ( <
                View style = {
                    {
                        marginBottom: windowHeight * 0.09,
                        height: windowHeight * 0.85,
                    }
                } >
                <
                FlatList inverted data = { messageDataController }
                renderItem = {
                    ({ item }) => renderItem(item)
                }
                refreshControl = { <
                    RefreshControl
                    colors = {
                        ['red']
                    }
                    tintColor = { 'red' }
                    refreshing = { refreshing }
                    onRefresh = {
                        () => {
                            onRefresh();
                        }
                    }
                    />
                }
                /> < /
                View >
            ) : ( <
                View >
                <
                Text > Bu alanda mesaj bulunmamaktadır < /Text> < /
                View >
            )
        } <
        View style = {
            {
                width: '100%',
                flexDirection: 'row',
                paddingBottom: 8,
                justifyContent: 'center',
                position: 'absolute',
                backgroundColor: 'gainsboro',
            }
        } >
        <
        View style = {
            {
                width: '80%',
                height: 50,
                backgroundColor: 'white',
                borderRadius: 20,
                marginLeft: 5,
                flexDirection: 'row',
                alignItems: 'center',
                justifyContent: 'space-between',
                paddingHorizontal: 10,
            }
        } >
        <
        TextInput style = {
            { width: '90%' }
        }
    placeholder = "Mesaj Yaz"
    value = { message }
    onChangeText = { onChangeText => setMessage(onChangeText) }
    /> {
    /* <TouchableOpacity>
                <Image
                  source={require('../../Assets/Images/documant.png')}
                  style={{height: 20, width: 20}}
                />
              </TouchableOpacity> */
} <
/View> <
TouchableOpacity onPress = {
    () => {
        SendMessage();
    }
}
style = {
        {
            width: 50,
            height: 50,
            borderRadius: 25,
            backgroundColor: 'green',
            alignItems: 'center',
            justifyContent: 'center',
            marginLeft: 5,
            opacity: 0.8,
        }
    } >
    <
    Image source = { require('../../Assets/Images/icon.png') }
style = {
    { height: 30, width: 30, tintColor: 'white' }
}
/> < /
TouchableOpacity > <
    /View> <
View style = {
        {
            position: 'absolute',
            width: windowWidth * 0.98,
            height: windowHeight * 0.93,
        }
    } >
    <
    TouchableOpacity onPress = {
        () => {
            Messages();
        }
    } >
    <
    Image source = { require('../../Assets/Images/sync.png') }
style = {
    { height: 20, width: 20 }
}
/> < /
TouchableOpacity > <
    /View> < /
View >
);
}